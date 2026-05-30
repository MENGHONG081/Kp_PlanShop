import React, { useState, useEffect } from 'react';
import QRCode from 'qrcode.react';
import apiClient from '@utils/api';

interface PaymentFormProps {
  orderId: string;
  amount: number;
  onPaymentSuccess?: (transactionId: string) => void;
}

export const PaymentForm: React.FC<PaymentFormProps> = ({
  orderId,
  amount,
  onPaymentSuccess,
}) => {
  const [qrCode, setQrCode] = useState<string>('');
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState<string>('');
  const [paymentStatus, setPaymentStatus] = useState<'pending' | 'success' | 'failed'>('pending');

  useEffect(() => {
    generateKhqrCode();
    const interval = setInterval(checkPaymentStatus, 5000);
    return () => clearInterval(interval);
  }, [orderId]);

  const generateKhqrCode = async () => {
    try {
      setIsLoading(true);
      const response = await apiClient.post('/payment/generate-khqr', {
        order_id: orderId,
        amount: amount,
      });

      if (response.data.success) {
        setQrCode(response.data.data.qr_code);
        setError('');
      } else {
        setError('Failed to generate QR code');
      }
    } catch (err: any) {
      setError(err.response?.data?.message || 'Error generating payment QR code');
    } finally {
      setIsLoading(false);
    }
  };

  const checkPaymentStatus = async () => {
    try {
      const response = await apiClient.get(`/payment/status/${orderId}`);

      if (response.data.data.status === 'completed') {
        setPaymentStatus('success');
        onPaymentSuccess?.(response.data.data.transaction_id);
      }
    } catch (err) {
      // Continue polling
    }
  };

  if (isLoading) {
    return (
      <div className="flex items-center justify-center py-12">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      </div>
    );
  }

  if (error && !qrCode) {
    return (
      <div className="bg-red-50 border border-red-200 rounded-lg p-4">
        <p className="text-red-800 font-medium mb-4">{error}</p>
        <button
          onClick={generateKhqrCode}
          className="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
        >
          Retry
        </button>
      </div>
    );
  }

  return (
    <div className="max-w-md mx-auto bg-white rounded-lg shadow-lg p-8">
      <h2 className="text-2xl font-bold mb-2">Payment Required</h2>
      <p className="text-gray-600 mb-6">Order ID: {orderId}</p>

      {paymentStatus === 'success' ? (
        <div className="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
          <p className="text-green-800 font-semibold">✓ Payment Completed</p>
          <p className="text-green-700 text-sm mt-2">Thank you for your payment!</p>
        </div>
      ) : (
        <>
          <div className="flex justify-center mb-6">
            {qrCode ? (
              <div className="border-2 border-gray-200 p-4 rounded-lg">
                <QRCode
                  value={qrCode}
                  size={256}
                  level="H"
                  includeMargin={true}
                  renderAs="canvas"
                />
              </div>
            ) : (
              <div className="h-64 w-64 bg-gray-100 rounded-lg flex items-center justify-center">
                <span className="text-gray-500">QR Code Loading...</span>
              </div>
            )}
          </div>

          <div className="bg-gray-50 rounded-lg p-4 mb-6">
            <p className="text-sm text-gray-600">Amount to Pay</p>
            <p className="text-3xl font-bold text-gray-900">${amount.toFixed(2)}</p>
          </div>

          <div className="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p className="text-sm text-blue-800">
              📱 Scan the QR code above using your KHQR-supported payment app
            </p>
          </div>

          {error && (
            <div className="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
              <p className="text-sm text-yellow-800">{error}</p>
            </div>
          )}

          <div className="pt-6 border-t border-gray-200">
            <p className="text-center text-sm text-gray-500">
              Payment will be verified automatically. Please wait...
            </p>
          </div>
        </>
      )}
    </div>
  );
};
