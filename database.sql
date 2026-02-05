BEGIN;

-- admins
CREATE TABLE admins (
  id BIGSERIAL PRIMARY KEY,
  username VARCHAR(50) UNIQUE,
  password VARCHAR(255),
  imguser VARCHAR(255)
);

-- categories
CREATE TABLE categories (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(50) UNIQUE
);

-- users
CREATE TABLE users (
  id BIGSERIAL PRIMARY KEY,
  fullname VARCHAR(100),
  email VARCHAR(100) UNIQUE,
  phone VARCHAR(20),
  password VARCHAR(255),
  active SMALLINT DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  imguser VARCHAR(255)
);

-- products
CREATE TABLE products (
  id BIGSERIAL PRIMARY KEY,
  name VARCHAR(100),
  category_id BIGINT,
  price NUMERIC(10,2),
  stock INT,
  description TEXT,
  image VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT products_category_fk
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- orders (ENUM -> VARCHAR + CHECK)
CREATE TABLE orders (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT,
  total NUMERIC(10,2),
  status VARCHAR(20) DEFAULT 'Pending' CHECK (status IN ('Pending','Cancelled','Done')),
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT orders_user_fk
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- order_items
CREATE TABLE order_items (
  id BIGSERIAL PRIMARY KEY,
  order_id BIGINT,
  product_id BIGINT,
  qty INT,
  unit_price NUMERIC(10,2),
  CONSTRAINT order_items_order_fk
    FOREIGN KEY (order_id) REFERENCES orders(id),
  CONSTRAINT order_items_product_fk
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- payments
CREATE TABLE payments (
  payment_id BIGSERIAL PRIMARY KEY,
  order_id BIGINT NOT NULL,
  amount NUMERIC(10,2) NOT NULL,
  payment_method VARCHAR(50) NOT NULL,
  payment_status VARCHAR(30) DEFAULT 'PENDING',
  transaction_ref VARCHAR(100),
  payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  failure_reason TEXT
  -- (Optional) add FK if you want:
  -- ,CONSTRAINT payments_order_fk FOREIGN KEY (order_id) REFERENCES orders(id)
);

-- discounts
CREATE TABLE discounts (
  id BIGSERIAL PRIMARY KEY,
  product_id BIGINT NOT NULL,
  discount_percent NUMERIC(5,2) NOT NULL,
  price_after_discount NUMERIC(10,2) NOT NULL,
  description VARCHAR(255),
  discount_date DATE NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT discounts_product_fk
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- customer_feedback
CREATE TABLE customer_feedback (
  id BIGSERIAL PRIMARY KEY,
  user_id BIGINT NOT NULL,
  comments TEXT NOT NULL,
  rating NUMERIC(2,1) DEFAULT 5.0,
  visible BOOLEAN DEFAULT FALSE,
  submitted_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT feedback_user_fk
    FOREIGN KEY (user_id) REFERENCES users(id)
);

COMMIT;
