export interface User {
  id: number;
  fullname: string;
  email: string;
  phone?: string;
  active: number;
  created_at?: string;
  updated_at?: string;
}

export interface LoginRequest {
  email: string;
  password: string;
}

export interface LoginResponse {
  success: boolean;
  message: string;
  data: {
    user: User;
    token: string;
    token_type: string;
  };
}

export interface RegisterRequest {
  fullname: string;
  email: string;
  phone?: string;
  password: string;
  password_confirmation: string;
}

export interface RegisterResponse {
  success: boolean;
  message: string;
  data: {
    user: User;
    token: string;
    token_type: string;
  };
}

export interface Order {
  id: number;
  order_id: string;
  user_id: number;
  amount: number;
  status: 'pending' | 'completed' | 'failed' | 'cancelled';
  payment_method: string;
  transaction_id?: string;
  created_at: string;
  updated_at: string;
}

export interface PaymentResponse {
  success: boolean;
  message: string;
  data: {
    qr_code: string;
    order_id: string;
    amount: number;
  };
}

export interface PaymentStatusResponse {
  success: boolean;
  data: {
    status: string;
    transaction_id: string;
    amount: number;
  };
}

export interface ApiError {
  success: boolean;
  message: string;
  errors?: Record<string, string>;
}
