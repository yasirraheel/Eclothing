# Email Notification System

## Overview
The system automatically sends email notifications for order events using SMTP settings configured in the admin panel.

## SMTP Configuration
SMTP settings are managed in **Admin Panel → Settings → SMTP Email Configuration**

Required fields:
- **SMTP Host** (e.g., smtp.gmail.com)
- **SMTP Port** (e.g., 587)
- **SMTP Username** (your email address)
- **SMTP Password** (app password for Gmail)
- **SMTP From Address** (sender email)
- **SMTP From Name** (sender name, e.g., "Eclothing")

## Email Notifications

### 1. Customer Notifications

#### Order Placed
**Trigger:** When customer completes checkout  
**Sent to:** Customer email  
**Content:**
- Order confirmation message
- Order number and date
- Complete list of items with prices
- Total amount
- Delivery address
- Payment method
- Order status (Pending)

#### Order Status Changed
**Trigger:** When admin updates order status  
**Sent to:** Customer email  
**Content varies by status:**

- **Pending** → "Your order is pending confirmation"
- **Processing** → "Your order is being processed"
- **Shipped** → "Your order has been shipped"
- **Delivered** → "Your order has been delivered"
- **Cancelled** → "Your order has been cancelled"

Each email includes:
- Status update message
- Order number and date
- Total amount
- Payment method

### 2. Admin Notifications

#### New Order Alert
**Trigger:** When customer places an order  
**Sent to:** Admin email (from settings)  
**Content:**
- Alert notification
- Customer information (name, email, phone, address)
- Complete order details
- Order items and quantities
- Total amount
- Payment method
- Direct link to view order in admin panel

## Email Templates

All emails use professional HTML templates with:
- Branded header with Eclothing colors
- Responsive design
- Clear formatting
- Status badges with color coding
- Footer with copyright

## Technical Details

### Mail Classes
- `App\Mail\OrderPlaced` - Customer order confirmation
- `App\Mail\OrderStatusChanged` - Order status updates
- `App\Mail\NewOrderAdmin` - Admin new order alert

### Email Views
- `resources/views/emails/order-placed.blade.php`
- `resources/views/emails/order-status-changed.blade.php`
- `resources/views/emails/new-order-admin.blade.php`

### Controllers
- `CheckoutController@store` - Sends order placed emails
- `Admin\OrderController@updateStatus` - Sends status change emails

## How It Works

1. **SMTP Configuration Loading**
   - `AppServiceProvider` loads SMTP settings from database on boot
   - Settings are applied to Laravel's mail configuration
   - All emails automatically use these settings

2. **Order Placement**
   - Customer completes checkout
   - Order is created in database
   - Email sent to customer with order details
   - Email sent to admin with new order alert

3. **Status Updates**
   - Admin changes order status in admin panel
   - If status changed, email sent to customer
   - Email content customized based on new status

## Error Handling

All email sending is wrapped in try-catch blocks:
- Errors are logged but don't break the order flow
- Orders are still created even if email fails
- Check Laravel logs for email sending errors

## Testing

To test email notifications:
1. Configure SMTP settings in admin panel
2. Place a test order as a customer
3. Check customer email for order confirmation
4. Check admin email for new order alert
5. Update order status in admin panel
6. Check customer email for status update

## Gmail Configuration

For Gmail SMTP:
1. Enable 2-Factor Authentication
2. Generate App Password (not your regular password)
3. Use these settings:
   - Host: smtp.gmail.com
   - Port: 587
   - Username: your-email@gmail.com
   - Password: your-app-password
   - Encryption: TLS (automatically set)

## Troubleshooting

**Emails not sending?**
1. Check SMTP settings in admin panel
2. Verify email credentials are correct
3. Check Laravel logs: `storage/logs/laravel.log`
4. Ensure admin email is set in settings
5. Test with a simple email client first

**Gmail blocking emails?**
1. Use App Password, not regular password
2. Enable "Less secure app access" (if not using 2FA)
3. Check Gmail's blocked senders

**Wrong sender name/email?**
- Update "SMTP From Address" and "SMTP From Name" in settings
