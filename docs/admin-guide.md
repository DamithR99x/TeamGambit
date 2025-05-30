# SmartCart Admin Guide

This guide provides detailed instructions for store administrators on how to manage the SmartCart platform.

## Accessing the Admin Dashboard

### Admin Login

1. Navigate to the SmartCart admin login page at http://localhost:8080/admin (or your configured domain).
2. Enter your admin email and password.
3. Click **Login** to access the admin dashboard.

### Dashboard Overview

The admin dashboard provides an overview of your store's performance:

- **Quick Statistics Cards**: Total orders, revenue, customers, and products
- **Recent Orders**: List of the most recent orders with status
- **Sales Chart**: Visual representation of sales over time
- **Low Stock Alerts**: Products that are running low on inventory
- **Latest Customers**: Recently registered customers

### Sneat Admin Interface

SmartCart's admin interface is built with the Sneat Bootstrap template, which provides:

- **Modern UI**: Clean, professional interface with modern design elements
- **Responsive Layout**: Works seamlessly on all device sizes
- **Dark/Light Modes**: Switch between dark and light themes for comfortable viewing
- **Interactive Components**: Dynamic charts, sortable tables, and intuitive forms

To toggle between dark and light modes:
1. Click on your profile icon in the top-right corner
2. Click the theme toggle switch in the dropdown menu

## Managing Products

### Viewing All Products

1. In the admin sidebar, click on **Products** > **All Products**.
2. View a list of all products with key information:
   - Product name
   - SKU
   - Price
   - Stock quantity
   - Status (active/inactive)
   - Created date

### Adding a New Product

1. Go to **Products** > **All Products**.
2. Click the **Add New Product** button.
3. Fill in the product details:
   - **Basic Information**:
     - Name
     - SKU (Stock Keeping Unit)
     - Description (using the rich text editor)
   - **Pricing**:
     - Regular price
     - Discount price (optional)
   - **Inventory**:
     - Stock quantity
     - Low stock threshold
   - **Categories**: Select one or more categories
   - **Images**: Upload product images
   - **Status**: Active, Inactive, or Draft
4. Click **Save** to create the product.

### Editing a Product

1. Go to **Products** > **All Products**.
2. Find the product you want to edit and click the **Edit** button.
3. Update the product information as needed.
4. Click **Save** to update the product.

### Managing Product Images

1. While editing a product, scroll to the **Images** section.
2. To add a new image:
   - Click **Add Image**.
   - Select an image file from your computer.
   - The image will upload automatically.
3. To set a primary image:
   - Hover over the image.
   - Click **Set as Primary**.
4. To delete an image:
   - Hover over the image.
   - Click the **Delete** (trash) icon.
5. To reorder images:
   - Drag and drop images into the desired order.

### Deleting a Product

1. Go to **Products** > **All Products**.
2. Find the product you want to delete.
3. Click the **Delete** button.
4. Confirm the deletion when prompted.

## Managing Categories

### Viewing All Categories

1. In the admin sidebar, click on **Products** > **Categories**.
2. View a list of all categories with their details:
   - Category name
   - Parent category (if applicable)
   - Number of products
   - Created date

### Adding a New Category

1. Go to **Products** > **Categories**.
2. Click the **Add New Category** button.
3. Fill in the category details:
   - Name
   - Slug (auto-generated, but can be edited)
   - Description (optional)
   - Parent Category (optional, for creating subcategories)
4. Click **Save** to create the category.

### Editing a Category

1. Go to **Products** > **Categories**.
2. Find the category you want to edit and click the **Edit** button.
3. Update the category information as needed.
4. Click **Save** to update the category.

### Deleting a Category

1. Go to **Products** > **Categories**.
2. Find the category you want to delete.
3. Click the **Delete** button.
4. Confirm the deletion when prompted.

## Managing Orders

### Viewing All Orders

1. In the admin sidebar, click on **Orders** > **All Orders**.
2. View a list of all orders with key information:
   - Order number
   - Customer name
   - Order status
   - Total amount
   - Order date

### Viewing Order Details

1. Go to **Orders** > **All Orders**.
2. Find the order you want to view and click on the order number.
3. View detailed order information:
   - Customer details
   - Shipping address
   - Order items with quantities and prices
   - Order totals
   - Order status history

### Updating Order Status

1. While viewing an order, locate the **Status** dropdown in the order details section.
2. Select the new status from the available options:
   - Pending
   - Processing
   - Completed
   - Cancelled
   - Refunded
3. (Optional) Add a comment to explain the status change.
4. Click **Update Status** to save the changes.
5. The customer will receive an email notification about the status change (if enabled).

### Generating Invoice

1. While viewing an order, click the **Generate Invoice** button.
2. A PDF invoice will be generated with all order details.
3. You can:
   - Download the invoice
   - Print the invoice
   - Email the invoice to the customer

## Managing Customers

### Viewing All Customers

1. In the admin sidebar, click on **Customers** > **All Customers**.
2. View a list of all registered customers with key information:
   - Name
   - Email
   - Registration date
   - Number of orders

### Viewing Customer Details

1. Go to **Customers** > **All Customers**.
2. Find the customer you want to view and click on their name.
3. View detailed customer information:
   - Personal details
   - Address information
   - Order history
   - Account status

### Editing Customer Information

1. While viewing a customer's details, click the **Edit** button.
2. Update the customer information as needed.
3. Click **Save** to update the customer profile.

## System Settings

### General Settings

1. In the admin sidebar, click on **Settings** > **General**.
2. Configure store-wide settings:
   - Store name
   - Store email
   - Currency
   - Default language
   - Store address
3. Click **Save Changes** to update the settings.

### Shipping Settings

1. Go to **Settings** > **Shipping**.
2. Configure shipping options:
   - Shipping zones
   - Shipping methods
   - Shipping rates
3. Click **Save Changes** to update the settings.

### Email Templates

1. Go to **Settings** > **Email Templates**.
2. Select a template to edit:
   - Order confirmation
   - Order status update
   - New account
   - Password reset
3. Customize the template content using the rich text editor.
4. Click **Save Template** to update.

## Dashboard Widgets

The SmartCart admin dashboard includes several widgets that provide at-a-glance information:

### Sales Overview Widget

This card displays key sales metrics:
- Total sales amount
- Comparison with previous period (percentage increase/decrease)
- Small sparkline chart showing sales trend

### Revenue Chart Widget

An interactive area chart showing:
- Revenue over time (daily, weekly, monthly)
- Ability to filter by date range
- Hover tooltips with detailed information

### Recent Orders Widget

A table of the most recent orders showing:
- Order number
- Customer name
- Order total
- Status (with color-coded badges)
- Date
- View button for quick access

### Top Products Widget

A bar chart showing the best-selling products:
- Product name
- Number of units sold
- Percentage of total sales

### Customer Stats Widget

A metrics card showing:
- Total number of customers
- New customers this period
- Repeat customer rate
- Average order value

## Reporting

### Sales Reports

1. In the admin sidebar, click on **Reports** > **Sales**.
2. View sales data with filtering options:
   - Date range
   - Product category
   - Customer
3. The report displays:
   - Total sales
   - Number of orders
   - Average order value
   - Sales chart

### Inventory Reports

1. Go to **Reports** > **Inventory**.
2. View inventory status for all products:
   - Current stock levels
   - Low stock items
   - Out of stock items
   - Stock movement history

### Customer Reports

1. Go to **Reports** > **Customers**.
2. View customer statistics:
   - New customers over time
   - Top customers by order value
   - Customer locations
   - Registration sources

## Account Management

### Updating Your Admin Profile

1. Click on your name in the top-right corner of the dashboard.
2. Select **My Profile** from the dropdown menu.
3. Update your information:
   - Name
   - Email
   - Password
4. Click **Save Changes** to update your profile.

### Managing Admin Users (Super Admin Only)

1. Go to **Settings** > **Admin Users**.
2. View all admin users with their roles.
3. To add a new admin:
   - Click **Add Admin User**.
   - Fill in their details and assign a role.
   - Click **Create User**.
4. To edit an admin user:
   - Click the **Edit** button next to their name.
   - Update their details or role.
   - Click **Save Changes**.
5. To delete an admin user:
   - Click the **Delete** button next to their name.
   - Confirm the deletion when prompted.

## Security

### Viewing Login History

1. Go to **Settings** > **Security**.
2. View login attempts to the admin dashboard:
   - Successful logins
   - Failed login attempts
   - IP addresses
   - Timestamps

### Changing Your Password

1. Click on your name in the top-right corner.
2. Select **Change Password**.
3. Enter your current password.
4. Enter and confirm your new password.
5. Click **Update Password** to save the changes.

## Getting Help

### Admin Documentation

1. Click on the **Help** button in the top-right corner.
2. Select **Documentation** from the dropdown menu.
3. Browse through the admin documentation organized by topic.

### Technical Support

1. Click on the **Help** button in the top-right corner.
2. Select **Contact Support** from the dropdown menu.
3. Fill out the support request form with details of your issue.
4. Click **Submit** to send your request to the technical team. 