import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { PaymentForm } from '@components/PaymentForm';
import apiClient from '@utils/api';
import { Order } from '@types/index';

export const Payment: React.FC = () => {
  const { orderId } = useParams<{ orderId: string }>();
  const navigate = useNavigate();
  const [order, setOrder] = useState<Order | null>(null);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    fetchOrder();
  }, [orderId]);

  const fetchOrder = async () => {
    try {
      setIsLoading(true);
      const response = await apiClient.get(`/orders/${orderId}`);
      setOrder(response.data.data);
      setError('');
    } catch (err: any) {
      setError('Failed to load order details');
    } finally {
      setIsLoading(false);
    }
  };

  if (isLoading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      </div>
    );
  }

  if (error || !order) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="text-center">
          <p className="text-red-600 text-lg mb-4">{error}</p>
          <button
            onClick={() => navigate('/dashboard')}
            className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
          >
            Back to Dashboard
          </button>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-100 py-12">
      <div className="max-w-4xl mx-auto">
        <button
          onClick={() => navigate('/dashboard')}
          className="mb-6 px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700"
        >
          ← Back to Dashboard
        </button>
        
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          <PaymentForm
            orderId={order.order_id}
            amount={order.amount}
            onPaymentSuccess={() => {
              setTimeout(() => navigate('/dashboard'), 2000);
            }}
          />
          
          <div className="bg-white rounded-lg shadow p-6">
            <h2 className="text-2xl font-bold mb-6">Order Details</h2>
            
            <div className="space-y-4">
              <div className="flex justify-between pb-4 border-b">
                <span className="text-gray-600">Order ID:</span>
                <span className="font-semibold">{order.order_id}</span>
              </div>
              
              <div className="flex justify-between pb-4 border-b">
                <span className="text-gray-600">Amount:</span>
                <span className="font-semibold">${order.amount.toFixed(2)}</span>
              </div>
              
              <div className="flex justify-between pb-4 border-b">
                <span className="text-gray-600">Status:</span>
                <span className={`font-semibold ${
                  order.status === 'completed' ? 'text-green-600' :
                  order.status === 'failed' ? 'text-red-600' :
                  'text-yellow-600'
                }`}>
                  {order.status.toUpperCase()}
                </span>
              </div>
              
              <div className="flex justify-between pb-4 border-b">
                <span className="text-gray-600">Payment Method:</span>
                <span className="font-semibold">{order.payment_method}</span>
              </div>
              
              {order.transaction_id && (
                <div className="flex justify-between pb-4 border-b">
                  <span className="text-gray-600">Transaction ID:</span>
                  <span className="font-semibold text-sm">{order.transaction_id}</span>
                </div>
              )}
              
              <div className="flex justify-between">
                <span className="text-gray-600">Date:</span>
                <span className="font-semibold">
                  {new Date(order.created_at).toLocaleString()}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
