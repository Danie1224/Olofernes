# TechStore E-Commerce Platform - Project Documentation

---

## 1. Project Overview

### 1.1 Project Description

**TechStore** is a comprehensive full-stack e-commerce application designed to provide businesses with a complete online retail solution. The application handles the entire customer lifecycle from product browsing and purchasing to order management, returns processing, and payment handling.

**Problem Solved:** Traditional e-commerce platforms often lack integrated solutions for order management, return processing, and promotional campaigns. TechStore combines these critical features into a single, unified platform that serves both customers and administrators seamlessly.

**Purpose:** To create a modern, scalable e-commerce platform that enables businesses to:
- Manage product catalogs and inventory
- Process customer orders securely
- Handle returns and refunds efficiently
- Run promotional campaigns with vouchers
- Provide customers with order tracking and management tools
- Enable administrators with comprehensive dashboards and controls

---

### 1.2 Target Users

**Primary Users:**

1. **Customers/Shoppers**
   - Characteristics: Online shoppers of technology products
   - Needs: Browse products, place orders, track purchases, manage returns, apply discount codes
   

2. **Administrators/Store Managers**
   - Characteristics: Business owner managing store operations
   - Needs: Manage inventory, process orders, approve returns, distribute vouchers, view analytics


---

### 1.3 Key Features

- **Product Management** - Browse, search, and filter technology products with detailed information
- **Shopping Cart & Checkout** - Add items to cart with secure checkout process
- **Order Management** - Users can view all their orders with detailed status tracking and item breakdowns
- **Payment Processing** - Multiple payment method support with secure transaction handling
- **Return Management System** - Customers can request returns with automatic refund calculation and admin approval workflow
- **Voucher Distribution** - Admins can create and distribute discount vouchers with flexible conditions (percentage or fixed amount)
- **Admin Dashboard** - Comprehensive administrative interface for managing:
  - Customer orders and order items
  - Return requests with approval/rejection workflows
  - Voucher creation and distribution
  - Order completion tracking
- **User Order Dashboard** - Customers view their complete order history (total orders, total spent, pending orders)
- **Order Details ** - Detailed views of individual orders with items, pricing.
- **Responsive Design** - Fully responsive interface that works seamlessly
---

### 1.4 Technology Stack

**Frontend:**
- **Vue.js/Nuxt.js** - Modern JavaScript framework for dynamic UI (located in `nuxt-techstore/`)
- **Vite** - Fast build tool and development server
- **Tailwind CSS 4.0** - Utility-first CSS framework for responsive design
- **Axios** - HTTP client for API communications

**Backend:**
- **Laravel 12** - PHP framework for robust API and web application logic
- **PHP 8.2+** - Server-side programming language
- **Laravel Sanctum** - API authentication and token management
- **Blade** - Laravel templating engine for server-side rendering

**Database:**
- **SQLite** - Lightweight embedded database for development/testing
- **MySQL/PostgreSQL** - Production-ready relational database (configurable)
- **Laravel Eloquent ORM** - Object-relational mapping for database interactions

**Key Models:**
- `User` - User accounts and authentication
- `Product`, `Brand` - Product catalog management
- `Order`, `OrderItem` - Order processing and tracking
- `ReturnRequest`, `ReturnRequestItem` - Return management
- `Payment` - Payment transaction records
- `Voucher`, `UserVoucher`, `VoucherUsage` - Promotional voucher system
- `Customer`, `Shipping`, `Admin` - Supporting business entities

**Development & DevOps Tools:**
- **Composer** - PHP dependency manager
- **npm/yarn** - JavaScript package management
- **Laravel Sail** - Docker development environment
- **PHPUnit** - Testing framework
- **Git** - Version control
- **Laravel Pint** - PHP code formatter
- **Postman/API Testing** - API validation and testing

**Additional Libraries:**
- **FakerPHP** - Test data generation
- **Mockery** - Mock object framework for testing
- **Laravel Pail** - Log monitoring tool
- **Laravel Tinker** - Interactive REPL for Laravel

---

## 2. App Plan

### 2.1 Project Scope

#### **IN SCOPE:**

**Core E-Commerce Features:**
- Product catalog management with brands and categories
- Customer registration and authentication (user & admin roles)
- Product browsing, searching, and filtering
- Shopping cart functionality
- Order creation and processing
- Order item tracking and management
- Payment processing and transaction records
- Shipping and delivery management
- User order history and dashboard

**Return Management System:**
- Customer-initiated return requests with itemized selection
- Return request creation form with reason selection
- Return request dashboard with filtering and statistics
- Admin review and approval/rejection workflow
- Automatic refund calculation
- Return request status tracking and timeline

**Voucher Distribution System:**
- Admin voucher creation with flexible discount types (percentage or fixed amount)
- Automatic voucher distribution to all users
- Batch voucher redistribution to new users
- Voucher usage tracking and statistics
- Voucher expiration date management
- Admin dashboard for voucher management

**Admin Features:**
- Admin authentication and authorization
- Admin dashboard with multiple management interfaces
- Order management dashboard with filtering
- Return request dashboard with approval workflows
- Voucher management and distribution controls
- Statistics and analytics for orders, returns, and voucher usage
- User voucher distribution tracking

**Customer Features:**
- User dashboard displaying all orders
- Order details view with item breakdown
- Order statistics (total orders, total spent, pending orders)
- Return request submission and tracking
- Return request history and status monitoring
- Voucher application at checkout
- Account management

**API Endpoints:**
- RESTful API for customers, products, orders, order items, shipping, vouchers, and cart
- Authentication endpoints (login, register, logout)
- Public and authenticated endpoint access control
- JSON response formatting

**Frontend User Interface:**
- Responsive web design (desktop, tablet, mobile)
- Blade template rendering for server-side views
- Nuxt.js/Vue.js components for frontend interactions
- Tailwind CSS styling
- Form validation and error handling
- dashboards

#### **OUT OF SCOPE:**

- Real-time inventory management and stock tracking
- Advanced analytics and reporting tools
- Customer review and rating system
- Product recommendation engine
- Wish list/favorites functionality
- Multi-language/internationalization support
- Advanced shipping integrations (real-time carrier APIs)
- Email notification system
- SMS notifications
- Social media integration
- Customer support/ticketing system
- Subscription/recurring order management
- Product variant management (size, color, etc.)
- Advanced payment gateway integrations (beyond basic payment processing)
- Loyalty/rewards program
- Customer segmentation and marketing automation
- Advanced search with Elasticsearch
- Mobile native apps (iOS/Android)
- Live chat support
- Marketplace/multi-vendor functionality
- Two-factor authentication (2FA)
- Order scheduling and pre-orders

---

### 2.2 Objectives & Goals

**Primary Objectives:**

1. **Deliver a Complete E-Commerce Platform**
   - Successfully implement a fully functional online retail system capable of handling product sales
   - Measurable: All core features (products, orders, payments) operational and tested
   - Timeline: Complete by project deadline

2. **Implement Comprehensive Order Management System**
   - Enable customers to view, track, and manage their orders seamlessly
   - Enable admins to review and process orders efficiently
   - Measurable: 100% order lifecycle features working (creation, viewing, completion tracking)
   - Success Criteria: Users can view all orders with detailed information and statistics

3. **Build Functional Return Request Management**
   - Create a complete return workflow from customer request to admin approval
   - Implement automatic refund calculations and tracking
   - Measurable: Return requests can be created, approved/rejected, and tracked
   - Success Criteria: Full audit trail of return status changes

4. **Develop Admin Voucher Distribution System**
   - Enable admins to create and distribute promotional vouchers
   - Implement automatic batch distribution to users
   - Measurable: Admins can create, edit, delete, and redistribute vouchers with full tracking
   - Success Criteria: Vouchers appear on all user accounts and can be applied at checkout

5. **Create User-Friendly Admin Dashboard**
   - Build comprehensive admin interfaces for managing orders, returns, and vouchers
   - Provide real-time statistics and filtering capabilities
   - Measurable: All admin features accessible from sidebar with clear navigation
   - Success Criteria: Admin can manage 3+ major feature areas from dashboard

6. **Ensure Data Security and Authorization**
   - Implement role-based access control (customer vs admin)
   - Protect all sensitive operations with proper authentication
   - Measurable: No unauthorized access to other users' data
   - Success Criteria: All endpoints secured with appropriate middleware

7. **Maintain Code Quality and Performance**
   - Follow Laravel/Vue.js best practices and conventions
   - Optimize database queries to prevent N+1 issues
   - Measurable: Zero critical security vulnerabilities, optimized queries
   - Success Criteria: Code passes linting, testing, and security reviews

8. **Provide Comprehensive Documentation**
   - Document all features, APIs, and implementation details
   - Create quick-start guides and reference materials
   - Measurable: All major features have accompanying documentation
   - Success Criteria: New developers can understand and extend the system

---

### 2.3 User Stories & Use Cases

#### **User Story 1: Customer Browses and Purchases Products**
**As a** customer,  
**I want to** browse the product catalog, view product details, add items to my cart, and complete a purchase,  
**So that** I can find and buy technology products conveniently.

**Acceptance Criteria:**
- [ ] Customer can view all available products with descriptions and prices
- [ ] Customer can filter/search products by category or brand
- [ ] Customer can view detailed product information including specifications
- [ ] Customer can add products to their shopping cart
- [ ] Customer can view and modify their cart contents
- [ ] Customer can proceed to checkout and complete a purchase
- [ ] Customer receives order confirmation with order number
- [ ] Customer sees their new order in their order history immediately

---

#### **User Story 2: Customer Views and Tracks Their Orders**
**As a** customer,  
**I want to** view all my past orders, see detailed information about each order, and track their status,  
**So that** I can monitor my purchases and know when they will arrive.

**Acceptance Criteria:**
- [ ] Customer can access their order history dashboard from navigation menu
- [ ] Customer can see a list of all their orders with key information (date, total, status)
- [ ] Customer can click on an order to view detailed information including all items purchased
- [ ] Customer can see order statistics (total orders, total spent, pending orders)
- [ ] Customer can filter orders by status (pending, completed, cancelled, etc.)
- [ ] Order details show shipping information and estimated delivery date
- [ ] Customer can print their order for records
- [ ] The dashboard is responsive and works on mobile devices

---

#### **User Story 3: Customer Requests a Return for Defective Items**
**As a** customer,  
**I want to** request a return for items that are defective or unsatisfactory and see the refund amount,  
**So that** I can get my money back without hassle.

**Acceptance Criteria:**
- [ ] Customer can access the return request creation form
- [ ] Customer can select from their previous orders
- [ ] Customer can select specific items from an order to return
- [ ] Customer can specify the quantity to return for each item
- [ ] Customer can select a reason from a predefined list
- [ ] Customer can add optional notes explaining the issue
- [ ] System automatically calculates the refund amount based on item prices
- [ ] Customer can submit the return request
- [ ] Customer receives confirmation of return request submission
- [ ] Customer can view their return request status in their returns dashboard
- [ ] Customer can see updates when admin approves or rejects the return
- [ ] Customer can track the refund timeline and status changes

---

#### **User Story 4: Admin Reviews and Approves Return Requests**
**As an** admin,  
**I want to** review all customer return requests, view item details and reasons, and approve or reject requests,  
**So that** I can manage returns efficiently and process refunds appropriately.

**Acceptance Criteria:**
- [ ] Admin can access the returns dashboard from the admin sidebar
- [ ] Admin can see a list of all return requests with customer and status information
- [ ] Admin can filter return requests by status (pending, approved, rejected, refunded)
- [ ] Admin can click on a return request to view full details including customer info and items
- [ ] Admin can see the reason provided by the customer for each item
- [ ] Admin can see the calculated refund amount
- [ ] Admin can click "Approve" button to accept a return request
- [ ] Admin can click "Reject" button and provide a rejection reason
- [ ] System tracks which admin processed each return (processed_by field)
- [ ] Admin can see a timeline of status changes for each return
- [ ] Dashboard shows statistics (total, pending, approved, refunded requests)
- [ ] Admin receives confirmation message after approving/rejecting

---

#### **User Story 5: Admin Creates and Distributes Promotional Vouchers**
**As an** admin,  
**I want to** create promotional vouchers with specific discount amounts or percentages, set expiration dates, and automatically distribute them to customers,  
**So that** I can run promotional campaigns and increase customer engagement.

**Acceptance Criteria:**
- [ ] Admin can access the voucher management section from the admin dashboard
- [ ] Admin can click "Create New Voucher" to open the creation form
- [ ] Admin can enter a voucher code (e.g., SUMMER20)
- [ ] Admin can select discount type (percentage or fixed amount)
- [ ] Admin can enter the discount value with real-time unit indicator
- [ ] Admin can set start and end dates for the voucher validity
- [ ] Admin can set a usage limit (how many times the voucher can be used)
- [ ] Admin can add an optional description
- [ ] Admin can set voucher status (active/inactive)
- [ ] When submitted, system automatically distributes voucher to all users
- [ ] Admin can see confirmation that distribution was successful
- [ ] Admin can view the voucher list showing all created vouchers
- [ ] Admin can edit an existing voucher and update its details
- [ ] Admin can delete a voucher if needed
- [ ] Admin can view voucher usage statistics (used, remaining, users)
- [ ] Admin can redistribute a voucher to newly registered users

---

#### **User Story 6: Customer Applies Voucher Code at Checkout**
**As a** customer,  
**I want to** apply a promotional voucher code to my order to receive a discount,  
**So that** I can save money on my purchase.

**Acceptance Criteria:**
- [ ] Customer can see a voucher/promo code field during checkout
- [ ] Customer can enter a voucher code they received
- [ ] System validates the code and shows the discount amount
- [ ] Discount is applied to the order total automatically
- [ ] Customer can see the updated total price with discount applied
- [ ] Customer can remove the voucher code if desired
- [ ] Error message displays if voucher code is invalid or expired
- [ ] Error message displays if voucher usage limit has been reached
- [ ] Voucher can only be used once per customer (if applicable)
- [ ] Order confirmation shows the discount applied

---

#### **User Story 7: Admin Views Dashboard Statistics**
**As an** admin,  
**I want to** see real-time statistics and summaries on my dashboard for orders, returns, and vouchers,  
**So that** I can quickly understand the business performance and make informed decisions.

**Acceptance Criteria:**
- [ ] Admin dashboard displays key statistics cards (total orders, total revenue, pending returns, active vouchers)
- [ ] Statistics update in real-time or refresh on page reload
- [ ] Admin can see a breakdown of order statuses (pending, completed, cancelled)
- [ ] Admin can see return request statistics (total, pending, approved, rejected)
- [ ] Admin can see voucher usage statistics (distributed, redeemed, expired)
- [ ] Admin can access detailed reports for each section
- [ ] Dashboard is responsive and displays well on all screen sizes
- [ ] Admin can export statistics for reporting purposes (optional)

---

#### **User Story 8: Customer Authenticates and Manages Account**
**As a** customer,  
**I want to** register for an account, log in securely, and manage my profile,  
**So that** I can access personalized features and keep my information current.

**Acceptance Criteria:**
- [ ] Customer can register with email and password
- [ ] Customer receives validation feedback for password strength
- [ ] Customer can log in with email and password
- [ ] Customer remains logged in across sessions (remember me option)
- [ ] Customer can view and edit their profile information
- [ ] Customer can change their password
- [ ] Customer can log out securely
- [ ] Session tokens are properly managed with Sanctum authentication
- [ ] Customer data is protected with proper authorization checks

---

#### **User Story 9: System Ensures Payment Processing**
**As a** customer,  
**I want to** provide payment information securely and receive confirmation that my payment was processed,  
**So that** I know my order will be fulfilled.

**Acceptance Criteria:**
- [ ] Customer can select a payment method at checkout
- [ ] Payment form is presented securely (HTTPS)
- [ ] Customer receives payment confirmation immediately
- [ ] Payment transaction is recorded in the system
- [ ] Order status is updated to "Paid" after successful payment
- [ ] Customer receives an email/notification confirming payment
- [ ] System handles payment failures gracefully with error messages
- [ ] Admin can view payment history and transaction details

---

#### **User Story 10: Admin Manages User Accounts and Permissions**
**As an** admin,  
**I want to** manage user accounts, set permissions, and control access to different system areas,  
**So that** I can ensure proper authorization and data security.

**Acceptance Criteria:**
- [ ] Admin can view a list of all registered users
- [ ] Admin can search and filter users by criteria
- [ ] Admin can view user account details (email, registration date, orders count)
- [ ] Admin can activate or deactivate user accounts
- [ ] Admin can manage admin user accounts separately
- [ ] Different admin levels can have different permissions (optional enhancement)
- [ ] System logs all admin actions for audit purposes
- [ ] Admin cannot modify another admin's account without permission
- [ ] Customer data is protected with role-based access control

---

### 2.4 System Architecture

#### **Overall Architecture Overview**

TechStore follows a **traditional three-tier architecture** with clear separation of concerns:

```
┌─────────────────────────────────────────────────────────────────┐
│                     PRESENTATION LAYER                           │
│  (Frontend - Vue.js/Nuxt.js + Blade Templates + Tailwind CSS)   │
│  ├─ Public Website (Nuxt.js)                                    │
│  ├─ Admin Dashboard (Blade + Vue Components)                    │
│  └─ Customer Dashboard (Blade Templates)                        │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                   APPLICATION LAYER                              │
│              (Backend - Laravel 12 + PHP 8.2)                   │
│  ├─ Web Routes (Blade rendering)                                │
│  ├─ API Routes (RESTful endpoints)                              │
│  ├─ Controllers (Business logic)                                │
│  │  ├─ ProductController                                        │
│  │  ├─ OrderController                                          │
│  │  ├─ UserReturnRequestController                              │
│  │  ├─ AdminVoucherController                                   │
│  │  ├─ AdminReturnController                                    │
│  │  └─ PaymentController                                        │
│  ├─ Models (Data abstraction)                                   │
│  │  ├─ Product, Brand                                           │
│  │  ├─ Order, OrderItem                                         │
│  │  ├─ ReturnRequest, ReturnRequestItem                         │
│  │  ├─ Voucher, UserVoucher, VoucherUsage                       │
│  │  ├─ Payment, Shipping                                        │
│  │  └─ User, Customer, Admin                                    │
│  ├─ Middleware (Authentication & Authorization)                │
│  │  ├─ auth:sanctum (API authentication)                        │
│  │  ├─ auth:admin (Admin routes)                                │
│  │  └─ Custom authorization policies                            │
│  └─ Services (Business logic encapsulation)                     │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                    DATA LAYER                                    │
│        (Database - SQLite / MySQL / PostgreSQL)                  │
│  ├─ Products Table (with brands and categories)                 │
│  ├─ Orders & OrderItems (transaction records)                   │
│  ├─ Customers (user information)                                │
│  ├─ Payments (payment transactions)                             │
│  ├─ ReturnRequests & ReturnRequestItems                         │
│  ├─ Vouchers, UserVouchers, VoucherUsage                        │
│  ├─ Shipping (delivery information)                             │
│  └─ Admins (administrative accounts)                            │
└─────────────────────────────────────────────────────────────────┘
```

#### **Component Interactions**

**Request Flow:**

1. **Frontend Initiation**
   - User interacts with Nuxt.js frontend or accesses Blade templates
   - Requests sent via HTTP/HTTPS to the backend

2. **Routing**
   - Laravel router directs requests to appropriate controllers
   - Two types of routes:
     - Web routes: Return Blade-rendered HTML pages
     - API routes: Return JSON responses for Nuxt.js

3. **Authentication & Authorization**
   - Sanctum validates API tokens for authenticated requests
   - Middleware checks user role (customer vs. admin)
   - Routes protected with auth guards

4. **Controller Logic**
   - Controllers handle business logic and data processing
   - Call Model methods for database operations
   - Implement validation and error handling

5. **Database Operations**
   - Eloquent ORM translates PHP to SQL queries
   - Models establish relationships between entities
   - Transactions ensure data integrity for complex operations

6. **Response Generation**
   - Controllers return Blade templates (rendered HTML) for web
   - Controllers return JSON responses for API
   - Frontend renders or displays responses to user

#### **Key Architectural Patterns**

- **MVC (Model-View-Controller)** - Separation of concerns
- **REST API** - Standardized endpoints for data access
- **ORM (Eloquent)** - Object-relational mapping for database abstraction
- **Middleware Pipeline** - Authentication, authorization, and request processing
- **Service Container** - Dependency injection for loose coupling
- **Repository Pattern** - Data access abstraction (optional enhancement)

#### **Data Flow Examples**

**Example 1: Create Return Request**
```
1. User fills form in Blade template
2. POST request sent to /returns endpoint
3. UserReturnRequestController@store handles request
4. ReturnRequest model saves data to database
5. ReturnRequestItem models save line items
6. Relationships automatically link items to order
7. Controller calculates refund amounts
8. Response redirected to /returns dashboard
9. Success message displayed to user
```

**Example 2: Admin Approves Return**
```
1. Admin clicks "Approve" button on return details
2. PATCH request sent to /admin/returns/{id}/approve
3. AdminReturnController@approve processes request
4. Model updates status to "approved"
5. Timestamp recorded in processed_at field
6. Admin ID tracked in processed_by field
7. Activity logged for audit trail
8. Email notification sent to customer (optional)
9. Response returned with updated data
10. Dashboard refreshes to show new status
```

---

## 3. UI/UX Design

### 3.1 Design Philosophy

TechStore follows a **modern, user-centric design approach** focused on:

- **Simplicity**: Minimize cognitive load with intuitive navigation and clear information hierarchy
- **Consistency**: Unified visual language across all pages and components
- **Accessibility**: WCAG 2.1 AA compliance ensuring usability for all users
- **Responsiveness**: Mobile-first approach with seamless experience across all devices
- **Performance**: Fast loading times and smooth interactions
- **Trust**: Professional design with clear security indicators and transparent processes

**Design Principles Applied:**

1. **User-Centered Design** - Every feature designed with user needs and workflows in mind
2. **Visual Hierarchy** - Important information emphasized through size, color, and position
3. **Feedback & Confirmation** - Clear messages for user actions and system responses
4. **Progressive Disclosure** - Show relevant information without overwhelming users
5. **Consistency** - Familiar patterns reduce learning curve for users
6. **Error Prevention** - Validation and confirmations prevent mistakes
7. **Aesthetic Integrity** - Design supports and enhances usability

---

### 3.2 Color Scheme

**TechStore Color Palette:**

- **Primary Color: Blue (#007BFF)**
  - Used for: Primary buttons, links, active states, and interactive elements
  - Represents: Trust, reliability, and technology
  - Examples: "Add to Cart" button, navigation highlights

- **Secondary Color: Gray (#6C757D)**
  - Used for: Secondary buttons, inactive states, disabled content
  - Represents: Neutral actions and supplementary information
  - Examples: "Cancel" buttons, secondary navigation

- **Accent Color: Green (#28A745)**
  - Used for: Success messages, approved status, positive confirmations
  - Represents: Success, completion, and positive actions
  - Examples: "Approve Return" button, success notifications

- **Danger Color: Red (#DC3545)**
  - Used for: Error messages, delete actions, rejection notifications
  - Represents: Caution and negative actions
  - Examples: "Reject Return" button, error alerts

- **Background Color: White (#FFFFFF)**
  - Used for: Main content areas and card backgrounds
  - Provides: Clean, readable surface for content

- **Light Background: Light Gray (#F8F9FA)**
  - Used for: Section backgrounds, subtle visual separation
  - Provides: Gentle contrast without distraction

- **Text Color: Dark Gray (#212529)**
  - Used for: Body text and primary content
  - Ensures: High readability and accessibility

- **Border Color: Light Gray (#DEE2E6)**
  - Used for: Dividers and input field borders
  - Provides: Subtle visual boundaries

**Accessible Color Combinations:**
- Dark text on light backgrounds (7:1 contrast ratio)
- Color-blind friendly palette (avoid red-green sole differentiation)
- Multiple indicators beyond color (icons, text labels, status badges)

---

### 3.3 Typography

**Font Stack:**

- **Font Family**: Inter, -apple-system, BlinkMacSystemFont, Segoe UI, sans-serif
  - Rationale: Modern, clean, highly legible sans-serif optimized for screens
  - Fallback: System fonts ensure consistency across platforms

**Text Hierarchy & Sizing:**

| Element | Size | Weight | Line Height | Usage |
|---------|------|--------|-------------|-------|
| H1 (Page Title) | 32px / 2rem | 700 (Bold) | 1.2 | Main page headings |
| H2 (Section Title) | 24px / 1.5rem | 700 (Bold) | 1.3 | Section headers |
| H3 (Subsection) | 20px / 1.25rem | 600 (SemiBold) | 1.4 | Subsection headers |
| H4 (Small Heading) | 16px / 1rem | 600 (SemiBold) | 1.5 | Component titles |
| Body Text | 16px / 1rem | 400 (Regular) | 1.5 | Main content |
| Small Text | 14px / 0.875rem | 400 (Regular) | 1.6 | Secondary information |
| Caption | 12px / 0.75rem | 400 (Regular) | 1.4 | Metadata, labels |
| Code/Monospace | 14px | 400 (Regular) | 1.5 | Technical content |

**Typography Scale Rationale:**
- 1.33x multiplier between sizes (golden ratio)
- Consistent line heights for readable text blocks
- Larger line height for smaller text (improves legibility)

**Weight Usage:**
- **700 Bold**: Primary headings (H1, H2)
- **600 SemiBold**: Secondary headings (H3, H4) and emphasized content
- **400 Regular**: Body text and most content
- **300 Light**: Subtle, supplementary information (dates, metadata)

---

### 3.4 UI Components

#### **Button Component**

**Primary Button (Blue)**
- Usage: Main call-to-action buttons
- Padding: 12px 24px (44px minimum height for touch)
- Border Radius: 6px
- Examples: "Add to Cart", "Create Return", "Approve"

**Secondary Button (Gray)**
- Usage: Alternative actions, less prominent
- Padding: 10px 20px
- Border Radius: 6px
- Examples: "Cancel", "Back"

**Danger Button (Red)**
- Usage: Destructive actions requiring confirmation
- Padding: 12px 24px
- Border Radius: 6px
- Examples: "Delete", "Reject Return"


#### **Input Fields**

**Text Input**
- Border: 1px solid #DEE2E6
- Padding: 10px 12px
- Border Radius: 6px
- Focus State: Blue border (#007BFF), subtle box-shadow
- Placeholder: Light gray (#6C757D)

**Form Validation:**
- Valid: Green left border with checkmark icon
- Invalid: Red left border with error icon
- Error Message: Red text below field (12px, 0.75rem)

**Input Types Supported:**
- Text, Email, Password, Number, Date, Textarea
- Autocomplete hints for enhanced UX
- Clear button (X) for text fields

#### **Navigation Bar**

**Structure:**
- Height: 60px (responsive, collapses to hamburger on mobile)
- Background: White with subtle shadow
- Logo/Brand: Left side, 24px x 24px
- Menu Items: Center alignment, hover underline
- User Menu: Right side, dropdown on click

**Menu Items:**
- Home, Products, About, Contact
- Authenticated users: My Orders, Returns, Account
- Admin users: Admin Dashboard, Reports
- Login/Logout buttons

**Mobile Navigation:**
- Hamburger menu icon
- Slide-out sidebar
- Full-width menu items
- Touch-friendly spacing (44px minimum)

#### **Cards**

**Product Card**
- Background: White
- Border: 1px solid #DEE2E6
- Border Radius: 8px
- Padding: 16px
- Hover Effect: Subtle shadow elevation, slight scale
- Content: Image, name, price, rating, "Add to Cart" button

**Order Card**
- Background: White
- Border-left: 4px solid (color by status)
- Padding: 16px
- Content: Order ID, date, total, status badge, "View Details" link
- Hover Effect: Background color change, cursor pointer

**Stat Card** (Dashboard)
- Background: Gradient (light to white)
- Border Radius: 8px
- Icon: Large, left-aligned
- Title: Small label
- Value: Large bold number
- Comparison: Previous period comparison percentage

**Status Cards** (Return/Voucher Details)
- Colored header bar matching status color
- Icon in header
- Title and description
- Related actions buttons

#### **Modal/Dialog**

**Confirmation Dialog**
- Overlay: Semi-transparent dark background (rgba(0, 0, 0, 0.5))
- Modal: White background, centered, max-width 500px
- Header: Title, close button
- Body: Message and context
- Footer: Cancel button (left), Confirm button (right)
- Animation: Fade in/slide down on open


#### **Tables**

**Data Table**
- Header: Bold text, light gray background (#F8F9FA)
- Rows: Alternating white background for readability
- Padding: 12px per cell
- Border: Subtle borders between rows
- Sortable Columns: Click header to sort, visual indicator
- Pagination: Bottom controls with page numbers
- Actions: Icon buttons (view, edit, delete) aligned right

**Responsive Behavior:**
- Desktop: Full table display
- Tablet: Horizontal scroll for overflow
- Mobile: Stacked card layout instead of table

#### **Status Badges**

**Badge Styles:**
- **Pending**: Gray background with gray text
- **Approved**: Green background with white text
- **Rejected**: Red background with white text
- **Completed**: Blue background with white text
- **Refunded**: Purple background with white text

**Badge Sizing:**
- Padding: 6px 12px
- Border Radius: 20px (pill shape)
- Font Size: 12px / 0.75rem
- Font Weight: 500 (Medium)

#### **Alert Messages**

**Success Alert**
- Background: Light green (#D4EDDA)
- Border-left: 4px solid green (#28A745)
- Icon: Checkmark
- Text: Dark green color
- Dismissible: X button

**Error Alert**
- Background: Light red (#F8D7DA)
- Border-left: 4px solid red (#DC3545)
- Icon: X or exclamation
- Text: Dark red color
- Auto-dismiss: 5 seconds (optional)

**Info Alert**
- Background: Light blue (#D1ECF1)
- Border-left: 4px solid blue (#007BFF)
- Icon: Information circle
- Text: Dark blue color

#### **Forms**

**Form Layout:**
- Required indicator: Red asterisk
- Help text: Small gray text below label
- Spacing: 16px between fields
- Validation feedback: Inline below field

**Form Sections:**
- Group related fields with subtle visual separation
- Optional fields marked as "Optional"
- Complex sections: Step indicator or accordion

#### **Pagination**

**Component Structure:**
- Previous/Next buttons
- Numbered page links (showing 5 pages max)
- Current page: Highlighted in blue
- Ellipsis: For skipped pages
- Disabled state: Previous on page 1, Next on last page

---

### 3.5 User Flows

#### **Flow 1: User Registration & Authentication**

```
START
  ↓
User visits application → Not authenticated
  ↓
Click "Register" button
  ↓
Enter email, password, confirm password
  ↓
System validates input
  ├─ Invalid? → Display error message → Back to form
  └─ Valid? → Continue
  ↓
System checks email uniqueness
  ├─ Email exists? → Display error → Back to form
  └─ New email? → Continue
  ↓
Hash password securely
  ↓
Create User record in database
  ↓
Generate Sanctum API token
  ↓
Store token in session/localStorage
  ↓
Redirect to dashboard
  ↓
User authenticated ✓
  ↓
END
```

#### **Flow 2: Making a Purchase**

```
START
  ↓
User browses products
  ↓
Click "Add to Cart" on product
  ↓
Quantity validation
  ├─ Invalid stock? → Display error
  └─ Valid? → Continue
  ↓
Add item to shopping cart
  ↓
Update cart count in navbar
  ↓
User continues shopping OR clicks "Proceed to Checkout"
  ↓
View cart page
  ├─ Modify quantities
  ├─ Remove items
  └─ Review total
  ↓
Click "Checkout"
  ↓
Verify user authentication
  ├─ Not logged in? → Redirect to login
  └─ Logged in? → Continue
  ↓
Enter/confirm shipping address
  ↓
Select shipping method
  ↓
Apply voucher code (optional)
  ├─ Valid code? → Calculate discount
  └─ Invalid? → Display error
  ↓
Review order summary
  ↓
Enter payment information
  ↓
Process payment
  ├─ Payment failed? → Display error, retry
  └─ Payment successful? → Continue
  ↓
Create Order record with status "Paid"
  ↓
Create OrderItem records for each cart item
  ↓
Mark UserVoucher as used (if applicable)
  ↓
Clear shopping cart
  ↓
Send order confirmation email
  ↓
Redirect to order confirmation page
  ↓
Order created ✓
  ↓
END
```

#### **Flow 3: Requesting a Return**

```
START
  ↓
User authenticated
  ↓
Click "Returns" in navigation
  ↓
View return requests dashboard
  ├─ Existing returns displayed
  ├─ Statistics shown
  └─ Filter by status
  ↓
Click "Request Return" button
  ↓
Select order from dropdown
  ↓
System displays order items
  ↓
Select specific items to return
  ├─ Check item checkboxes
  └─ Set quantity for each
  ↓
For each item: Select return reason
  ├─ Options: Defective, Wrong Item, Not as Described, Changed Mind, Other
  └─ Add optional notes
  ↓
System calculates refund amount
  ├─ Based on item prices
  └─ Display total refund
  ↓
Review return request
  ↓
Click "Submit Return Request"
  ↓
Validation
  ├─ All required fields? → Continue
  └─ Missing fields? → Display error
  ↓
Create ReturnRequest record with status "Pending"
  ↓
Create ReturnRequestItem records for each item
  ↓
Send confirmation email to customer
  ↓
Redirect to return details page
  ↓
Return created ✓
  ↓
END
```

#### **Flow 4: Admin Approving a Return**

```
START
  ↓
Admin logs in
  ↓
Click "Returns" in sidebar
  ↓
View admin returns dashboard
  ├─ All return requests listed
  ├─ Filter by status
  └─ Statistics displayed
  ↓
Click on return request to view details
  ↓
Review return information
  ├─ Customer details
  ├─ Order information
  ├─ Items being returned
  ├─ Reasons provided
  └─ Refund amount
  ↓
Admin decision
  ├─ Click "Approve"?
  │  ├─ Enter optional notes
  │  └─ Continue to approve
  ├─ Click "Reject"?
  │  ├─ Enter rejection reason (required)
  │  └─ Continue to reject
  └─ Leave pending? → End flow
  ↓
Process decision
  ├─ Approve: Update status to "approved"
  ├─ Reject: Update status to "rejected"
  └─ Record admin_id and timestamp
  ↓
Create status change log entry
  ↓
Send notification email to customer
  ├─ Approved: Include return shipping label info
  └─ Rejected: Include reason
  ↓
Update dashboard statistics
  ↓
Return processed ✓
  ↓
END
```

#### **Flow 5: Creating and Distributing Vouchers**

```
START
  ↓
Admin logs in
  ↓
Click "Voucher Management" in sidebar
  ↓
View vouchers list
  ├─ All vouchers displayed
  ├─ Statistics shown
  └─ Filter options available
  ↓
Click "Create New Voucher"
  ↓
Enter voucher details
  ├─ Code (e.g., SUMMER20)
  ├─ Description
  ├─ Discount type (percentage or fixed)
  ├─ Discount value
  ├─ Start date
  ├─ End date
  ├─ Usage limit
  └─ Status (active/inactive)
  ↓
System validates input
  ├─ Valid? → Continue
  └─ Invalid? → Display error
  ↓
Check code uniqueness
  ├─ Code exists? → Display error
  └─ Unique? → Continue
  ↓
Create Voucher record
  ↓
System auto-distributes to all users
  ├─ Get all User records
  ├─ Create UserVoucher for each user
  └─ Batch insert for efficiency
  ↓
Send email to all users with code
  ↓
Display success message
  ├─ Quantity distributed shown
  └─ Confirmation details
  ↓
Redirect to voucher details page
  ↓
Voucher created & distributed ✓
  ↓
END
```

---

## 4. Database Architecture (ERD)

### 4.1 Entity Relationship Diagram

```
┌────────────────────────────────────────────────────────────────────────────────────────┐
│                         TECHSTORE DATABASE ENTITIES & RELATIONSHIPS                   │
└────────────────────────────────────────────────────────────────────────────────────────┘

                            ┌─────────────────┐
                            │     USERS       │
                            ├─────────────────┤
                            │ id (PK)         │
                            │ name            │
                            │ email           │◄──┐
                            │ password        │   │
                            │ email_verified  │   │
                            │ created_at      │   │
                            │ updated_at      │   │
                            └─────────────────┘   │
                                    ▲             │
                                    │             │
                ┌───────────────────┼─────────────┼─────────────────────┐
                │                   │             │                     │
                │                   │             │                     │
                │                   │             │                     │
        ┌───────▼─────┐     ┌──────▼──┐     ┌────▼──────────┐   ┌──────▼─────────┐
        │   ORDERS    │     │ ADMINS  │     │ USER_VOUCHERS │   │  RETURN_REQUESTS│
        ├─────────────┤     ├─────────┤     ├───────────────┤   ├─────────────────┤
        │ id (PK)     │     │ id (PK) │     │ id (PK)       │   │ id (PK)         │
        │ user_id(FK) │────►│ user_id │     │ user_id (FK)  │   │ user_id (FK)    │
        │ customer_id │     │ email   │     │ voucher_id(FK)│   │ order_id (FK)   │
        │ order_date  │     │ role    │     │ is_used       │   │ status          │
        │ total_price │     │ created │     │ used_at       │   │ reason          │
        │ status      │     │ updated │     │ created_at    │   │ notes           │
        │ created_at  │     └─────────┘     └───────────────┘   │ processed_by(FK)│
        │ updated_at  │           ▲              ▲               │ created_at      │
        └─────────────┘           │              │               │ updated_at      │
               │                  │              │               └─────────────────┘
               │                  │              │                      │
               │                  │         ┌────▼─────────────┐        │
               │                  │         │    VOUCHERS     │        │
               │                  │         ├─────────────────┤        │
               │                  └────────►│ id (PK)         │        │
               │                            │ code            │        │
               │                            │ discount_type   │        │
               │                            │ discount_value  │        │
               │                            │ start_date      │        │
               │                            │ end_date        │        │
               │                            │ usage_limit     │        │
               │                            │ status          │        │
               │                            │ created_at      │        │
               │                            └─────────────────┘        │
               │                                                        │
               │                 ┌──────────────────────────┐           │
               │                 │   RETURN_REQUEST_ITEMS   │◄──────────┘
               │                 ├──────────────────────────┤
               │                 │ id (PK)                  │
               │                 │ return_request_id (FK)   │
               │                 │ order_item_id (FK)       │
               │                 │ quantity_returned        │
               │                 │ reason                   │
               │                 │ refund_amount            │
               │                 │ created_at               │
               │                 └──────────────────────────┘
               │
        ┌──────▼──────────┐
        │   ORDER_ITEMS   │
        ├─────────────────┤
        │ id (PK)         │
        │ order_id (FK)   │
        │ product_id (FK) │
        │ quantity        │
        │ price           │
        │ created_at      │
        └─────────────────┘
                │
                │
        ┌───────▼──────────┐
        │    PRODUCTS      │
        ├──────────────────┤
        │ id (PK)          │
        │ name             │
        │ description      │
        │ brand_id (FK)    │
        │ price            │
        │ stock_quantity   │
        │ created_at       │
        └──────────────────┘
                │
                │
        ┌───────▼──────────┐
        │     BRANDS       │
        ├──────────────────┤
        │ id (PK)          │
        │ name             │
        │ description      │
        │ created_at       │
        └──────────────────┘


    ┌──────────────────────┐      ┌──────────────────┐
    │   VOUCHER_USAGE      │      │    PAYMENTS      │
    ├──────────────────────┤      ├──────────────────┤
    │ id (PK)              │      │ id (PK)          │
    │ voucher_id (FK)      │      │ order_id (FK)    │
    │ user_id (FK)         │      │ amount           │
    │ used_at              │      │ payment_method   │
    │ order_id (FK)        │      │ status           │
    │ created_at           │      │ transaction_id   │
    └──────────────────────┘      │ created_at       │
                                  └──────────────────┘

    ┌──────────────────────┐      ┌──────────────────┐
    │    CUSTOMERS         │      │    SHIPPING      │
    ├──────────────────────┤      ├──────────────────┤
    │ id (PK)              │      │ id (PK)          │
    │ user_id (FK)         │      │ order_id (FK)    │
    │ first_name           │      │ address          │
    │ last_name            │      │ city             │
    │ phone                │      │ state            │
    │ address              │      │ postal_code      │
    │ created_at           │      │ tracking_number  │
    │ updated_at           │      │ status           │
    └──────────────────────┘      │ created_at       │
                                  └──────────────────┘
```

**Legend:**
- PK = Primary Key
- FK = Foreign Key
- ◄──► = One-to-Many relationship
- Lines show database relationships

---

### 4.2 Entity Descriptions

#### **Entity 1: USERS**

| Field | Data Type | Constraints | Description |
|-------|-----------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique user identifier |
| name | VARCHAR(255) | NOT NULL | User's full name |
| email | VARCHAR(255) | NOT NULL, UNIQUE | User's email address |
| email_verified_at | TIMESTAMP | NULLABLE | Email verification timestamp |
| password | VARCHAR(255) | NOT NULL | Hashed password |
| remember_token | VARCHAR(100) | NULLABLE | Remember me token |
| created_at | TIMESTAMP | NOT NULL | Account creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

---

#### **Entity 2: ADMINS**

| Field | Data Type | Constraints | Description |
|-------|-----------|-------------|-------------|
| admin_id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique admin identifier |
| name | VARCHAR(255) | NOT NULL | Admin's full name |
| email | VARCHAR(255) | NOT NULL, UNIQUE | Admin's email address |
| password | VARCHAR(255) | NOT NULL | Hashed password |
| role | ENUM('admin') | DEFAULT 'admin' | Admin role (extensible for future) |
| remember_token | VARCHAR(100) | NULLABLE | Remember me token |
| created_at | TIMESTAMP | NOT NULL | Account creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

---

#### **Entity 3: PRODUCTS**

| Field | Data Type | Constraints | Description |
|-------|-----------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique product identifier |
| name | VARCHAR(255) | NOT NULL | Product name |
| description | LONGTEXT | NULLABLE | Detailed product description |
| brand_id | BIGINT | FOREIGN KEY (brands.id) | Reference to brand |
| price | DECIMAL(10,2) | NOT NULL | Product price |
| stock_quantity | INT | NOT NULL, DEFAULT 0 | Available inventory count |
| created_at | TIMESTAMP | NOT NULL | Product creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

---

#### **Entity 4: BRANDS**

| Field | Data Type | Constraints | Description |
|-------|-----------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique brand identifier |
| name | VARCHAR(255) | NOT NULL, UNIQUE | Brand name |
| description | TEXT | NULLABLE | Brand description |
| created_at | TIMESTAMP | NOT NULL | Brand creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

---

#### **Entity 5: ORDERS**

| Field | Data Type | Constraints | Description |
|-------|-----------|-------------|-------------|
| order_id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique order identifier |
| customer_id | VARCHAR(255) | FOREIGN KEY (customers.customer_id) | Reference to customer |
| product_id | BIGINT | FOREIGN KEY (products.product_id) | Reference to product |
| admin_id | BIGINT | FOREIGN KEY (admins.admin_id) | Assigned admin |
| quantity | INT | DEFAULT 1 | Quantity ordered |
| total_price | DECIMAL(10,2) | NOT NULL | Order total amount |
| payment_method | ENUM('cash','card','gcash','paypal') | DEFAULT 'cash' | Payment method |
| status | ENUM('pending','processing','shipped','completed','cancelled') | DEFAULT 'pending' | Order status |
| order_date | TIMESTAMP | NOT NULL | Order placement timestamp |
| cancelled_at | TIMESTAMP | NULLABLE | Cancellation timestamp |
| completed_at | TIMESTAMP | NULLABLE | Completion timestamp |
| created_at | TIMESTAMP | NOT NULL | Record creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

---

#### **Entity 6: ORDER_ITEMS**

| Field | Data Type | Constraints | Description |
|-------|-----------|-------------|-------------|
| order_item_id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique order item identifier |
| order_id | BIGINT | FOREIGN KEY (orders.order_id) | Reference to order |
| product_id | BIGINT | FOREIGN KEY (products.product_id) | Reference to product |
| quantity | INT | NOT NULL | Item quantity ordered |
| unit_price | DECIMAL(10,2) | NOT NULL | Price per unit |
| subtotal | DECIMAL(10,2) | NOT NULL | Total for this line item |
| created_at | TIMESTAMP | NOT NULL | Record creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

---

#### **Entity 7: VOUCHERS**

| Field | Data Type | Constraints | Description |
|-------|-----------|-------------|-------------|
| voucher_id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique voucher identifier |
| code | VARCHAR(255) | NOT NULL, UNIQUE | Voucher code (e.g., SUMMER20) |
| admin_id | BIGINT | FOREIGN KEY (admins.admin_id), NULLABLE | Reference to admin who created it |
| description | TEXT | NULLABLE | Voucher description |
| discount_type | ENUM('percentage','fixed') | NOT NULL | Type of discount |
| discount_value | DECIMAL(10,2) | NOT NULL | Discount amount or percentage |
| start_date | DATE | NOT NULL | Voucher validity start date |
| end_date | DATE | NOT NULL | Voucher validity end date |
| usage_limit | INT | NULLABLE | Maximum uses allowed |
| usage_count | INT | DEFAULT 0 | Current usage count |
| status | ENUM('active','inactive','expired') | DEFAULT 'active' | Voucher status |
| created_at | TIMESTAMP | NOT NULL | Voucher creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

---

#### **Entity 8: USER_VOUCHERS**

| Field | Data Type | Constraints | Description |
|-------|-----------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique record identifier |
| user_id | BIGINT | FOREIGN KEY (users.id) | Reference to user |
| voucher_id | BIGINT | FOREIGN KEY (vouchers.id) | Reference to voucher |
| is_used | BOOLEAN | DEFAULT false | Whether voucher has been used |
| used_at | TIMESTAMP | NULLABLE | Timestamp of voucher use |
| created_at | TIMESTAMP | NOT NULL | Distribution timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

---

#### **Entity 9: VOUCHER_USAGE**

| Field | Data Type | Constraints | Description |
|-------|-----------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique usage record identifier |
| voucher_id | BIGINT | FOREIGN KEY (vouchers.id) | Reference to voucher |
| user_id | BIGINT | FOREIGN KEY (users.id) | Reference to user |
| order_id | BIGINT | FOREIGN KEY (orders.id) | Reference to order |
| used_at | TIMESTAMP | NOT NULL | Timestamp of usage |
| created_at | TIMESTAMP | NOT NULL | Record creation timestamp |

---

#### **Entity 10: PAYMENTS**

| Field | Data Type | Constraints | Description |
|-------|-----------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique payment identifier |
| order_id | BIGINT | FOREIGN KEY (orders.id) | Reference to order |
| amount | DECIMAL(10,2) | NOT NULL | Payment amount |
| payment_method | VARCHAR(50) | NOT NULL | Payment method (credit_card, bank_transfer, etc.) |
| status | ENUM('pending','completed','failed') | DEFAULT 'pending' | Payment status |
| transaction_id | VARCHAR(255) | NULLABLE | External transaction reference |
| created_at | TIMESTAMP | NOT NULL | Payment timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

---

#### **Entity 11: CUSTOMERS**

| Field | Data Type | Constraints | Description |
|-------|-----------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique customer identifier |
| user_id | BIGINT | FOREIGN KEY (users.id) | Reference to user account |
| first_name | VARCHAR(255) | NOT NULL | Customer's first name |
| last_name | VARCHAR(255) | NOT NULL | Customer's last name |
| phone | VARCHAR(20) | NULLABLE | Customer's phone number |
| address | TEXT | NULLABLE | Physical address |
| created_at | TIMESTAMP | NOT NULL | Record creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

---

#### **Entity 12: SHIPPING**

| Field | Data Type | Constraints | Description |
|-------|-----------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique shipping record identifier |
| order_id | BIGINT | FOREIGN KEY (orders.id) | Reference to order |
| address | TEXT | NOT NULL | Shipping address |
| city | VARCHAR(100) | NOT NULL | City |
| state | VARCHAR(100) | NULLABLE | State/Province |
| postal_code | VARCHAR(20) | NOT NULL | Postal code |
| tracking_number | VARCHAR(100) | NULLABLE | Carrier tracking number |
| status | ENUM('pending','shipped','delivered','returned') | DEFAULT 'pending' | Shipping status |
| created_at | TIMESTAMP | NOT NULL | Record creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

---

#### **Entity 13: RETURN_REQUESTS**

| Field | Data Type | Constraints | Description |
|-------|-----------|-------------|-------------|
| id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique return request identifier |
| user_id | BIGINT | FOREIGN KEY (users.id) | Reference to customer |
| order_id | BIGINT | FOREIGN KEY (orders.id) | Reference to original order |
| status | ENUM | DEFAULT 'pending' | Return status (pending, approved, rejected, refunded) |
| reason | TEXT | NULLABLE | Reason for return |
| notes | TEXT | NULLABLE | Additional customer notes |
| refund_amount | DECIMAL(10,2) | NULLABLE | Calculated refund amount |
| processed_by | BIGINT | FOREIGN KEY (admins.admin_id) | Admin who processed return |
| processed_at | TIMESTAMP | NULLABLE | Timestamp of admin action |
| created_at | TIMESTAMP | NOT NULL | Return request creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

---

#### **Entity 14: RETURN_REQUEST_ITEMS**

| Field | Data Type | Constraints | Description |
|-------|-----------|-------------|-------------|
| return_request_item_id | BIGINT | PRIMARY KEY, AUTO_INCREMENT | Unique return item identifier |
| request_id | BIGINT | FOREIGN KEY (return_requests.request_id) | Reference to return request |
| order_item_id | BIGINT | FOREIGN KEY (order_items.order_item_id) | Reference to original order item |
| product_id | BIGINT | FOREIGN KEY (products.product_id), NULLABLE | Reference to product |
| quantity | INT | NOT NULL, DEFAULT 1 | Quantity being returned |
| refund_amount | DECIMAL(10,2) | NULLABLE | Refund amount for this item |
| status | ENUM('pending','approved','rejected','refunded') | DEFAULT 'pending' | Status of individual return item |
| notes | TEXT | NULLABLE | Additional notes for this return item |
| requested_at | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Timestamp when return was requested |
| created_at | TIMESTAMP | NOT NULL | Record creation timestamp |
| updated_at | TIMESTAMP | NOT NULL | Last update timestamp |

---

### 4.3 Relationships

#### **Relationship 1: CUSTOMERS ← One-to-Many → ORDERS**
**Description:** Each customer can place multiple orders. Orders reference customers via customer_id (VARCHAR string primary key). Enables order history tracking per customer.

#### **Relationship 2: ORDERS ← One-to-Many → ORDER_ITEMS**
**Description:** Each order contains one or more items. OrderItems act as a junction table linking orders to products with quantity and pricing information.

#### **Relationship 3: PRODUCTS ← One-to-Many → ORDER_ITEMS**
**Description:** Each product can appear in multiple orders. This tracks which products have been sold and their sales history.

#### **Relationship 4: BRANDS ← One-to-Many → PRODUCTS**
**Description:** Each brand has multiple products. This enables product categorization and brand-based filtering.

#### **Relationship 5: ADMINS ← One-to-Many → ORDERS**
**Description:** Each admin can be assigned to manage multiple orders. Tracks which admin is responsible for order fulfillment.

#### **Relationship 6: VOUCHERS ← One-to-Many → USER_VOUCHERS**
**Description:** Each voucher is distributed to multiple users. This enables bulk promotional distribution while tracking individual availability status.

#### **Relationship 7: USERS ← One-to-Many → USER_VOUCHERS**
**Description:** Each user can receive multiple vouchers. USER_VOUCHERS maintains unique constraint on (user_id, voucher_id) to prevent duplicate distributions.

#### **Relationship 8: VOUCHERS ← One-to-Many → VOUCHER_USAGES**
**Description:** Each voucher can be used in multiple transactions. Tracks actual usage history separately from distribution (USER_VOUCHERS).

#### **Relationship 9: USERS ← One-to-Many → VOUCHER_USAGES**
**Description:** Each user's voucher usage is tracked in VOUCHER_USAGES. Maintains record of when and where vouchers are applied.

#### **Relationship 10: PRODUCTS ← One-to-Many → VOUCHER_USAGES**
**Description:** Each product can have vouchers applied across multiple usage records. Enables tracking of promotion effectiveness per product.

#### **Relationship 11: ADMINS ← One-to-Many → VOUCHERS**
**Description:** Admins can create and manage vouchers. admin_id field is nullable, allowing system-generated vouchers without admin assignment.

#### **Relationship 12: ORDERS ← One-to-Many → PAYMENTS**
**Description:** Each order has associated payment record(s). PAYMENTS tracks order payment status, method, and transaction details.

#### **Relationship 13: CUSTOMERS ← One-to-Many → PAYMENTS**
**Description:** Customer payment history is tracked. PAYMENTS references both order and customer for payment reconciliation.

#### **Relationship 14: ORDERS ← One-to-Many → SHIPPING**
**Description:** Each order has one shipping record containing delivery address, tracking, and delivery status information.

#### **Relationship 15: ORDERS ← One-to-Many → RETURN_REQUESTS**
**Description:** Orders can have multiple return requests. Returns reference the original order for validation and refund processing.

#### **Relationship 16: CUSTOMERS ← One-to-Many → RETURN_REQUESTS**
**Description:** Customer-initiated returns are tracked via customer_id. Enables return request history per customer.

#### **Relationship 17: PRODUCTS ← One-to-Many → RETURN_REQUESTS**
**Description:** Return requests reference specific products being returned. Tracks return patterns per product.

#### **Relationship 18: RETURN_REQUESTS ← One-to-Many → RETURN_REQUEST_ITEMS**
**Description:** Each return request contains multiple items being returned. Mirrors ORDER_ITEMS structure for detailed return tracking.

#### **Relationship 19: ORDER_ITEMS ← One-to-Many → RETURN_REQUEST_ITEMS**
**Description:** Return request items reference original order items. Maintains link between purchase and return for refund calculation.

#### **Relationship 20: PRODUCTS ← One-to-Many → RETURN_REQUEST_ITEMS**
**Description:** Product-level return tracking. Enables analytics on which products have higher return rates.

---

### 4.4 Database Normalization

The TechStore database follows **Third Normal Form (3NF)** normalization principles:

#### **First Normal Form (1NF) Compliance:**
✅ **Atomic Values:** All fields contain single, indivisible values
- Example: Address stored as separate fields (address, city, state, postal_code) rather than single concatenated field
- No repeating groups; related items stored in separate tables

✅ **Primary Keys:** Every entity has a unique primary key
- User IDs, Product IDs, Order IDs, etc. ensure unique identification

#### **Second Normal Form (2NF) Compliance:**
✅ **Full Functional Dependency:** All non-key attributes fully depend on the entire primary key
- In ORDER_ITEMS: product_id and quantity both depend on the entire composite relationship (order_id + product_id)
- In USER_VOUCHERS: voucher status depends on the combination of user_id and voucher_id

✅ **No Partial Dependencies:** Non-key attributes don't depend on part of a composite key
- Price at purchase stored in ORDER_ITEMS, not derived from PRODUCTS
- Ensures historical accuracy of pricing

#### **Third Normal Form (3NF) Compliance:**
✅ **No Transitive Dependencies:** Non-key attributes don't depend on other non-key attributes
- Product price doesn't depend on brand; product info stored in PRODUCTS table
- Customer name doesn't depend on order info; CUSTOMERS table separate from ORDERS
- Eliminates data redundancy

#### **Specific 3NF Examples:**

**Example 1: Product Pricing**
```
CORRECT (3NF):
- PRODUCTS: id, name, description, brand_id, price
- ORDER_ITEMS: id, order_id, product_id, quantity, price (price at time of purchase)

INCORRECT (Violates 3NF):
- ORDER_ITEMS: id, order_id, product_id, quantity, product_name, brand_name, current_price
  (redundantly stores product info that can be fetched from PRODUCTS)
```

**Example 2: Shipping Information**
```
CORRECT (3NF):
- ORDERS: id, user_id, customer_id, total_price, status
- SHIPPING: id, order_id, address, city, state, postal_code, tracking_number, status

INCORRECT (Violates 3NF):
- ORDERS: id, user_id, shipping_address, shipping_city, tracking_number
  (Shipping concerns mixed with order details)
```

#### **Benefits of 3NF Normalization:**

| Benefit | Impact |
|---------|--------|
| **Data Integrity** | Changes to master data (products, brands) automatically reflected everywhere |
| **Reduced Redundancy** | Single source of truth for each data element |
| **Easier Maintenance** | Updates needed in only one place |
| **Improved Performance** | Fewer duplicate records mean faster queries |
| **Scalability** | Clean structure supports future growth and features |

#### **Normalization Decisions:**

1. **Separate CUSTOMERS from USERS:** Keeps authentication (USERS) separate from profile data (CUSTOMERS) following Single Responsibility Principle

2. **Separate ORDER_ITEMS:** Creates junction table between ORDERS and PRODUCTS, supporting many-to-many relationship properly

3. **Separate RETURN_REQUEST_ITEMS:** Mirrors ORDER_ITEMS structure to properly track which specific items are being returned

4. **Separate VOUCHER_USAGE:** Tracks actual usage separately from distribution (USER_VOUCHERS) to maintain complete audit trail

5. **Store Price at Purchase:** ORDER_ITEMS stores price at time of purchase, not derived from current PRODUCTS price, preserving historical accuracy

---

## 5. Application Features & Functionality

### 5.1 Feature 1: Product Catalog & Shopping Cart

**Description:** Customers can browse a comprehensive product catalog, view detailed product information, add items to a shopping cart, and manage their cart before checkout.

**Functionality:**
- **Product Browsing:** View all products with images, names, descriptions, prices, and stock status
- **Product Filtering:** Filter by brand, price range, and availability
- **Product Details:** Click individual products to view specifications, reviews, and detailed descriptions
- **Add to Cart:** Add products to shopping cart with quantity selection
- **Cart Management:** View cart contents, modify quantities, remove items, view running total
- **Persistent Cart:** Cart persists across browser sessions (localStorage for frontend, database for logged-in users)
- **Stock Validation:** System prevents adding out-of-stock items or exceeding available inventory
- **Cart Summary:** Quick view of items count and total price in navbar

**Implementation Details:**
- **Frontend:** Nuxt.js/Vue.js with Axios for API calls
- **API Endpoints:** 
  - GET `/api/techstore/products` (list with filters)
  - GET `/api/techstore/products/{id}` (details)
- **Cart Storage:** LocalStorage for anonymous users, database for authenticated users
- **Real-time Updates:** Cart count updates immediately on item addition
- **Validation:** Quantity and stock validation before cart addition

---

### 5.2 Feature 2: Order Management & Order History

**Description:** Customers can view their complete order history, access detailed information about each order, track order status, and monitor order progress through the fulfillment pipeline.

**Functionality:**
- **Order History Dashboard:** View paginated list of all customer orders (10 per page)
- **Order Statistics:** Display total orders, total spent, pending orders in statistics cards
- **Order Details:** Click order to view full details including:
  - Order ID and placement date
  - All items purchased with quantities and prices
  - Order total and payment method
  - Shipping address and status
  - Estimated delivery date
- **Status Tracking:** Visual status indicators (pending, completed, cancelled)
- **Status Filtering:** Filter orders by status for quick lookup
- **Print Orders:** Print order details for records and bookkeeping
- **Responsive Design:** Works seamlessly on mobile, tablet, and desktop
- **Order Actions:** View details, request return, track shipment

**Implementation Details:**
- **Routes:**
  - GET `/orders` - Dashboard
  - GET `/orders/{orderId}` - Details
  - GET `/orders-stats` - Statistics JSON
- **Controller:** `UserOrderItemController@index()`, `show()`, `stats()`
- **Authorization:** Users can only see their own orders
- **Pagination:** Laravel pagination (10 items per page)
- **Relationships:** Eager loading to prevent N+1 queries
- **Performance:** Optimized queries with selects and joins

---

### 5.3 Feature 3: Return Request Management

**Description:** Provides a complete return workflow where customers can initiate return requests, admins can review and approve/reject requests, and refunds are automatically calculated and tracked.

**Functionality:**

**Customer-Side:**
- **Return Dashboard:** View all return requests with status indicators and filtering
- **Request Creation:** Submit new return requests with:
  - Order selection from past purchases
  - Itemized selection (select specific items from order)
  - Quantity specification
  - Reason selection (Defective, Wrong Item, Not as Described, Changed Mind, Other)
  - Optional notes
- **Auto Calculation:** System automatically calculates refund amount
- **Status Tracking:** Real-time status updates (pending, approved, rejected, refunded)
- **Timeline View:** See history of status changes with timestamps

**Admin-Side:**
- **Returns Dashboard:** List all return requests with filtering by status
- **Review Details:** View full return information including:
  - Customer details
  - Original order information
  - Items being returned with reasons
  - Calculated refund amount
- **Approval Workflow:** Approve or reject returns with:
  - Approval button with optional notes
  - Rejection button with required reason
- **Audit Trail:** Track which admin processed each return and when
- **Statistics:** Dashboard cards showing total, pending, approved, rejected, refunded counts

**Implementation Details:**
- **Controllers:**
  - `UserReturnRequestController` (customer operations)
  - `AdminReturnController` (admin operations)
- **Routes:**
  - GET `/returns` - Customer dashboard
  - GET `/returns/create` - Form
  - POST `/returns` - Submit request
  - GET `/returns/{id}` - Details
  - GET `/admin/returns` - Admin dashboard
  - PATCH `/admin/returns/{id}/approve` - Approve
  - PATCH `/admin/returns/{id}/reject` - Reject
- **Models:** ReturnRequest, ReturnRequestItem with relationships`
- **Validation:** Ensures items belong to customer's orders, quantity limits
- **Calculation:** Refund calculated based on OrderItem prices
- **Authorization:** Row-level security ensures users see only their returns

---

### 5.4 Feature 4: Voucher Distribution System

**Description:** Admin feature enabling creation of promotional vouchers with flexible discount options and automatic distribution to customers. Includes usage tracking and redistribution capabilities.

**Functionality:**

**Admin-Side:**
- **Voucher Creation:** Create vouchers with:
  - Unique code generation (e.g., SUMMER20)
  - Discount type selection (percentage or fixed amount)
  - Discount value with real-time unit indicator
  - Start and end dates for validity period
  - Usage limit specification
  - Status management (active/inactive)
- **Auto Distribution:** Automatically distributes to all existing users on creation
- **Batch Management:** 
  - Edit existing vouchers and update details
  - Delete vouchers with cascade delete of distributions
  - View distribution statistics
- **Redistribution:** Distribute vouchers to newly registered users
- **Usage Tracking:** Monitor voucher usage with statistics:
  - Total distributed count
  - Usage count
  - Remaining uses
- **Sidebar Integration:** Easy access from admin dashboard

**Customer-Side:**
- **Voucher Application:** Apply voucher codes at checkout
- **Validation:** System validates code, checks expiration, and usage limits
- **Discount Display:** Shows discount amount in order summary
- **One-Time Use:** Prevents double usage of single-use vouchers

**Implementation Details:**
- **Controller:** `AdminVoucherController` with 9 methods
- **Routes:** 9 admin voucher routes (CRUD + redistribute + stats)
- **Batch Insert:** Efficient bulk distribution using batch insert
- **Transaction Safety:** Database transactions ensure consistency
- **Models:** Voucher, UserVoucher, VoucherUsage with relationships
- **Authorization:** Admin-only access with `auth:admin` middleware
- **Statistics:** Real-time stats via `/admin/vouchers/{id}/stats` API
- **Validation:** Code uniqueness, date ranges, value validation

---

### 5.5 Feature 5: Admin Dashboard & Analytics

**Description:** Comprehensive administrative interface providing real-time insights into business operations with multiple management modules and statistics.

**Functionality:**
- **Multiple Dashboards:**
  - Orders Dashboard: View and filter customer orders
  - Returns Dashboard: Review and process return requests
  - Voucher Dashboard: Manage promotional campaigns
- **Statistics Cards:** Display key metrics:
  - Total orders and order count
  - Total revenue/sales
  - Pending orders count
  - Return request statistics (total, pending, approved, rejected)
  - Voucher statistics (distributed, used, expired)
- **Filtering & Search:** Filter by status, date range, customer
- **Pagination:** Handle large datasets efficiently
- **Action Buttons:** Quick access to approve, reject, view details
- **Sidebar Navigation:** Easy access to all management features
- **Responsive Design:** Works on all screen sizes

**Implementation Details:**
- **Controllers:** Multiple admin controllers (AdminReturnController, AdminVoucherController, AdminOrderController)
- **Views:** Blade templates with gradient styling
- **Statistics:** Real-time calculations and API endpoints
- **Authorization:** Middleware restricts access to admin users
- **Performance:** Optimized queries with pagination and indexing

---

## 6. Security & Error Handling

### 6.1 Security Measures Implemented

#### **Authentication:**
- **User Registration & Login:** Email-based authentication with password hashing using bcrypt
- **Admin Authentication:** Separate admin login with role-based access control
- **Session Management:** Laravel sessions with secure cookies
- **Remember Me:** Optional persistent login tokens
- **Sanctum Tokens:** API authentication using Laravel Sanctum Bearer tokens
- **Token Generation:** Secure token generation for API access: `$user->createToken('api')->plainTextToken`

#### **Authorization:**
- **Role-Based Access Control (RBAC):**
  - Customer role: Can view own orders, create returns, apply vouchers
  - Admin role: Can manage orders, process returns, create vouchers
- **Route Protection:**
  - Web routes: Protected with `auth` middleware
  - Admin routes: Protected with `auth:admin` middleware
  - API routes: Protected with `auth:sanctum` middleware
- **Row-Level Security:**
  - Customers can only view their own orders, returns, and vouchers
  - Admins can view all data but process specific items
  - Query scoping ensures users don't access other users' data

#### **Input Validation:**
- **Server-Side Validation:**
  - All form inputs validated in controllers before database operations
  - Comprehensive validation rules for each entity
  - Custom validation rules for complex constraints (date ranges, numeric ranges)
  
- **Validation Examples:**
  ```
  Orders: user_id must belong to authenticated user
  Returns: order_id and items must belong to user's orders
  Vouchers: code must be unique, dates must be valid, value must be positive
  Payments: amount must match order total
  ```

- **Client-Side Validation:**
  - Form field validation in Vue.js/Blade
  - Provides immediate user feedback
  - Prevents unnecessary server requests

- **Sanitization:**
  - HTML entities encoded in output
  - Blade template escaping: `{{ $variable }}`
  - User-provided data sanitized before storage

#### **Data Protection:**
- **Password Hashing:** bcrypt algorithm with automatic salting
- **Encryption:**
  - Sensitive configuration in `.env` file (not in code)
  - Payment tokens encrypted in database
  - Session data encrypted by default in Laravel
  
- **HTTPS Enforcement:**
  - All communications over SSL/TLS in production
  - Secure cookie flag set: `HTTP_ONLY`, `SECURE`, `SAME_SITE`
  
- **Database Security:**
  - Parameterized queries via Eloquent ORM
  - No raw SQL concatenation
  - Database passwords not in version control

#### **Protection Against Vulnerabilities:**

1. **CSRF Protection:**
   - CSRF tokens generated for all forms
   - Token validation on form submission
   - Token included in all state-changing requests (POST, PUT, DELETE, PATCH)
   - Protection automatically applied by Laravel middleware

2. **SQL Injection Prevention:**
   - Eloquent ORM prevents SQL injection through parameterized queries
   - Example: `Order::where('user_id', $userId)` uses bound parameters
   - No raw SQL queries without proper binding

3. **XSS (Cross-Site Scripting) Prevention:**
   - Blade template escaping: `{{ $data }}` automatically escapes HTML
   - Raw output only with `{!! $data !!}` when intentional
   - User input displayed through escaped output
   - JavaScript code never built from user input

4. **Authentication Bypass Prevention:**
   - Password reset tokens with expiration (1 hour default)
   - Session invalidation on logout
   - Brute force protection (optional, can be added)
   - Account lockout after failed attempts (optional enhancement)

5. **Authorization Bypass Prevention:**
   - Policies define what users can do (optional, currently using middleware)
   - Request validation ensures user owns the resource
   - Admin check before sensitive operations
   - Query scoping prevents accessing other users' data

6. **Sensitive Data Exposure:**
   - Passwords never logged or displayed
   - Payment info not stored (processed by external gateway)
   - API tokens never sent in plain text (HTTPS only)
   - Error messages don't expose system details

---

### 6.2 Error Handling

#### **Application-Level Error Handling:**

**Exception Handling Pipeline:**
```
Exception occurs
  ↓
Laravel exception handler catches it
  ↓
Logs error with full context
  ↓
Generates appropriate HTTP response
  ├─ Production: Generic error message
  └─ Development: Detailed error information
  ↓
Returns response to user
```

#### **Error Response Examples:**

**Validation Errors (422 Unprocessable Entity):**
```json
{
  "message": "Validation failed",
  "errors": {
    "email": ["The email field must be a valid email."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

**Authorization Errors (403 Forbidden):**
```json
{
  "message": "This action is unauthorized."
}
```

**Not Found Errors (404):**
```json
{
  "message": "Order not found"
}
```

**Server Errors (500):**
```
Production: "Something went wrong. Please try again later."
Development: Full stack trace with error details
```

#### **Error Logging:**
- **Log Files:** `storage/logs/laravel.log`
- **Logged Information:**
  - Error message and type
  - Stack trace with file and line numbers
  - Request information (method, URL, user ID)
  - Timestamp
- **Log Levels:** debug, info, notice, warning, error, critical, alert, emergency
- **Error Monitoring:** Can integrate with Sentry or similar services

#### **User-Facing Error Messages:**
- **Form Validation:**
  - Field-specific error messages below input
  - Red border on invalid field
  - Clear explanation of requirement

- **Action Errors:**
  - Toast notifications or alert messages
  - Color-coded (red for errors, green for success)
  - Auto-dismiss or require acknowledgment
  - Specific error details when helpful

- **Business Logic Errors:**
  - "Cannot return items from completed orders"
  - "Voucher has expired or reached usage limit"
  - "Insufficient stock available"

#### **Error Prevention:**
- **Validation Before Action:** Validate data before database operations
- **Constraint Violations:** Graceful handling of database constraint errors
- **Concurrent Operations:** Handle race conditions (e.g., simultaneous purchases of last item)
- **Transaction Rollback:** Automatic rollback on error, no partial updates
- **State Verification:** Check state before state-changing operations (e.g., can only approve pending returns)

#### **Debug Mode:**
- **Development:** `APP_DEBUG=true` in `.env` shows detailed errors
- **Production:** `APP_DEBUG=false` hides sensitive information
- **Log Visibility:** Full logs available to developers, hidden from users

---

## 9. Code Quality & Documentation

### 9.1 Code Structure

#### **Project Directory Layout**

```
techstore/
├── app/                          # Laravel application core
│   ├── Http/
│   │   ├── Controllers/          # Request handlers (ProductController, OrderController, etc.)
│   │   ├── Middleware/           # Authentication & authorization (auth:sanctum, auth:admin)
│   │   ├── Requests/             # Form request validation classes
│   │   └── Resources/            # API response transformations
│   ├── Models/                   # Eloquent ORM models
│   │   ├── User.php              # Customer user model
│   │   ├── Admin.php             # Admin user model
│   │   ├── Product.php           # Product catalog
│   │   ├── Order.php             # Order transactions
│   │   ├── OrderItem.php         # Order line items
│   │   ├── ReturnRequest.php     # Return management
│   │   ├── ReturnRequestItem.php # Individual return items
│   │   ├── Voucher.php           # Promotional vouchers
│   │   ├── UserVoucher.php       # User voucher distribution
│   │   ├── VoucherUsage.php      # Voucher usage tracking
│   │   ├── Payment.php           # Payment transactions
│   │   ├── Customer.php          # Customer profile data
│   │   ├── Brand.php             # Product brands
│   │   └── Shipping.php          # Shipping information
│   ├── Services/                 # Business logic services
│   │   ├── OrderService.php      # Order processing logic
│   │   ├── ReturnService.php     # Return handling logic
│   │   ├── VoucherService.php    # Voucher distribution & validation
│   │   └── PaymentService.php    # Payment processing logic
│   ├── Notifications/            # Email & notification classes
│   └── Providers/                # Service providers for DI container
├── bootstrap/
│   ├── app.php                   # Application container setup
│   └── cache/                    # Runtime caches
├── config/                       # Configuration files
│   ├── app.php                   # Application configuration
│   ├── database.php              # Database connections
│   ├── sanctum.php               # API authentication config
│   ├── mail.php                  # Email configuration
│   └── queue.php                 # Background job configuration
├── database/
│   ├── migrations/               # Database schema migrations
│   │   ├── *_create_users_table.php
│   │   ├── *_create_products_table.php
│   │   ├── *_create_orders_table.php
│   │   ├── *_create_order_items_table.php
│   │   ├── *_create_return_requests_table.php
│   │   ├── *_create_vouchers_table.php
│   │   └── *_create_payments_table.php
│   ├── seeders/                  # Database seeders
│   │   ├── DatabaseSeeder.php    # Master seeder
│   │   ├── UserSeeder.php        # Create test customers
│   │   ├── AdminSeeder.php       # Create admin accounts
│   │   ├── ProductSeeder.php     # Populate products & brands
│   │   ├── OrderSeeder.php       # Create sample orders
│   │   └── VoucherSeeder.php     # Create sample vouchers
│   └── database.sqlite           # SQLite development database
├── nuxt-techstore/               # Nuxt.js frontend application
│   ├── components/               # Reusable Vue components
│   │   ├── ProductCard.vue       # Product display component
│   │   ├── CartItem.vue          # Shopping cart item
│   │   ├── OrderCard.vue         # Order display
│   │   └── Modal.vue             # Reusable modal component
│   ├── pages/                    # Page components (auto-routed by Nuxt)
│   │   ├── index.vue             # Home page
│   │   ├── products/
│   │   │   ├── index.vue         # Product catalog
│   │   │   └── [id].vue          # Product details
│   │   ├── cart.vue              # Shopping cart
│   │   ├── checkout.vue          # Checkout process
│   │   ├── dashboard/
│   │   │   ├── orders.vue        # Customer orders
│   │   │   └── returns.vue       # Return requests
│   │   ├── auth/
│   │   │   ├── login.vue         # Customer login
│   │   │   └── register.vue      # Customer registration
│   │   └── admin/                # Admin pages
│   │       ├── dashboard.vue     # Admin dashboard
│   │       ├── orders.vue        # Order management
│   │       ├── returns.vue       # Return management
│   │       └── vouchers.vue      # Voucher management
│   ├── composables/              # Reusable Vue 3 composition functions
│   │   ├── useCart.ts            # Shopping cart logic
│   │   ├── useAuth.ts            # Authentication logic
│   │   └── useFetch.ts           # API calling utilities
│   ├── stores/                   # Pinia state management
│   │   ├── authStore.ts          # Authentication state
│   │   ├── cartStore.ts          # Cart state
│   │   └── userStore.ts          # User state
│   ├── layouts/
│   │   ├── default.vue           # Default layout
│   │   └── admin.vue             # Admin layout
│   ├── app.vue                   # Root component
│   ├── nuxt.config.ts            # Nuxt configuration
│   └── package.json              # Node.js dependencies
├── public/                       # Static files (images, icons)
│   ├── images/
│   │   ├── products/             # Product images
│   │   ├── brands/               # Brand logos
│   │   └── icons/                # UI icons
│   └── favicon.ico
├── resources/
│   ├── css/                      # Global styles
│   │   ├── app.css               # Application styles
│   │   └── tailwind.css          # Tailwind CSS configuration
│   ├── views/                    # Blade templates
│   │   ├── layouts/
│   │   │   ├── app.blade.php     # Main layout
│   │   │   └── admin.blade.php   # Admin layout
│   │   ├── auth/                 # Auth templates
│   │   ├── dashboard/            # Customer dashboard templates
│   │   └── admin/                # Admin dashboard templates
│   └── js/
│       └── bootstrap.js          # Bootstrap JavaScript
├── routes/
│   ├── api.php                   # API routes (/api/techstore/*)
│   ├── web.php                   # Web routes (Blade rendering)
│   └── admin.php                 # Admin routes (admin.php middleware)
├── storage/
│   ├── logs/
│   │   └── laravel.log           # Application logs
│   ├── app/                      # Application storage
│   ├── framework/                # Framework cache
│   │   ├── cache/
│   │   ├── sessions/
│   │   └── views/
│   └── uploads/                  # User uploads (if any)
├── tests/
│   ├── Feature/                  # Feature/integration tests
│   │   ├── OrderTest.php
│   │   ├── ReturnRequestTest.php
│   │   └── VoucherTest.php
│   ├── Unit/                     # Unit tests
│   │   ├── Models/
│   │   └── Services/
│   └── TestCase.php              # Base test class
├── .env.example                  # Environment variables template
├── .gitignore                    # Git ignore patterns
├── artisan                       # Laravel CLI tool
├── composer.json                 # PHP dependencies
├── composer.lock                 # Locked PHP versions
├── package.json                  # Node.js dependencies
├── package-lock.json             # Locked Node.js versions
├── vite.config.js                # Vite build configuration
├── tailwind.config.js            # Tailwind CSS configuration
├── phpunit.xml                   # PHPUnit test configuration
└── README.md                     # Project overview

```

#### **Key Directories Explained**

**`app/`** - Heart of the Laravel application
- Contains all application logic (models, controllers, services)
- Organized by responsibility (Http, Models, Services, Notifications)
- Business logic isolated in Service classes

**`config/`** - Configuration management
- Database, cache, queue, mail, and authentication settings
- Environment-based configuration via .env file
- Centralized configuration access via `config()` helper

**`database/`** - Database schema and data
- Migrations control schema version control
- Seeders populate test/sample data
- SQLite file for development, connection config for production

**`nuxt-techstore/`** - Frontend single-page application
- Nuxt.js 3.x with Vue 3 composition API
- File-based routing via pages/ directory
- Components, stores, and composables for reusability

**`resources/`** - Frontend assets and Blade templates
- CSS files (Tailwind) and JavaScript
- Blade templates for server-rendered content
- Views organized by feature

**`routes/`** - URL routing definitions
- API routes for JSON endpoints
- Web routes for server-rendered pages
- Admin routes with auth:admin middleware

**`storage/`** - Runtime data and logs
- Logs for debugging and monitoring
- Cache for performance
- Application-generated files

**`tests/`** - Automated test suites
- Feature tests for end-to-end workflows
- Unit tests for individual components
- TestCase base class for setup/teardown

---

### 9.2 Code Standards

#### **Naming Conventions**

**PHP (Laravel) Code:**
```php
// Classes: PascalCase
class OrderController { }
class ReturnRequest { }
class VoucherService { }

// Methods & Functions: camelCase
public function createReturnRequest() { }
public function calculateRefund() { }
private function validateVoucher() { }

// Variables: camelCase
$totalPrice = 0;
$userOrders = [];
$isApproved = true;

// Constants: CONSTANT_CASE
const MAX_CART_ITEMS = 100;
const DEFAULT_DISCOUNT = 10;
define('COMMISSION_RATE', 0.15);

// Table names: plural, snake_case
create_table('users');
create_table('products');
create_table('order_items');
create_table('return_requests');

// Database columns: snake_case
$table->string('first_name');
$table->integer('stock_quantity');
$table->timestamp('created_at');
$table->decimal('total_price', 10, 2);

// Relationships: camelCase method names
public function orders() { }
public function returnRequests() { }
public function products() { }
```

**JavaScript/Vue.js (Frontend) Code:**
```javascript
// Components: PascalCase
ProductCard.vue
OrderHistory.vue
VoucherManager.vue

// Variables & Functions: camelCase
const userData = {};
function calculateTotal() { }
const fetchProducts = async () => { }

// Constants: CONSTANT_CASE
const API_BASE_URL = 'http://localhost:8000/api/techstore/';
const DEFAULT_PAGE_SIZE = 10;

// CSS Classes: kebab-case
class="product-card"
class="order-item__header"
class="btn btn-primary"

// Store modules: camelCase
authStore.ts
cartStore.ts
userStore.ts
```

---

#### **Code Style & Formatting**

**PHP Code Style (PSR-12 Standard):**

```php
// Use type hints for parameters and return types
public function updateOrder(int $orderId, array $data): Order
{
    // Validate input
    $validated = validator()->validate($data);
    
    // Business logic
    $order = Order::findOrFail($orderId);
    $order->update($validated);
    
    return $order;
}

// Laravel conventions
// - Use dependency injection in constructors
// - Type hints for all parameters
// - Meaningful variable names
// - Single responsibility methods

class OrderService
{
    public function __construct(
        protected OrderRepository $orders,
        protected PaymentService $payments
    ) {}
    
    public function processOrder(array $data): Order
    {
        // Implementation
    }
}
```

**JavaScript/Vue Code Style (ESLint + Prettier):**

```javascript
// Use TypeScript where possible
interface Product {
  id: number;
  name: string;
  price: number;
}

// Vue 3 Composition API pattern
<script setup lang="ts">
import { ref, computed } from 'vue';

const items = ref<Product[]>([]);
const total = computed(() => items.value.reduce((sum, item) => sum + item.price, 0));

const addItem = async (product: Product) => {
  // Implementation
};
</script>

// Template formatting
<template>
  <div class="container">
    <header class="header">
      <h1>{{ title }}</h1>
    </header>
    
    <main class="main">
      <div v-if="items.length > 0" class="items-grid">
        <ProductCard 
          v-for="item in items" 
          :key="item.id"
          :product="item"
          @add="addItem"
        />
      </div>
      <EmptyState v-else message="No items found" />
    </main>
  </div>
</template>
```

**Configuration Files:**

```javascript
// .eslintrc.json - ESLint configuration
{
  "env": {
    "browser": true,
    "es2021": true,
    "node": true
  },
  "extends": [
    "eslint:recommended",
    "plugin:vue/vue3-recommended",
    "prettier"
  ],
  "rules": {
    "no-unused-vars": "warn",
    "no-console": "warn",
    "vue/multi-word-component-names": "off"
  }
}

// .prettierrc - Prettier code formatter
{
  "semi": true,
  "singleQuote": true,
  "trailingComma": "es5",
  "printWidth": 100,
  "tabWidth": 2
}
```

**Laravel Pint (PHP Formatter):**

```bash
# Format all PHP files according to PSR-12
./vendor/bin/pint

# Format specific directory
./vendor/bin/pint app/Models
```

---

#### **Comments & Documentation**

**PHP Code Documentation (PHPDoc):**

```php
/**
 * Process a customer return request
 * 
 * Creates a new ReturnRequest record with associated items,
 * calculates the refund amount, and triggers notification email.
 * 
 * @param int $orderId The ID of the order being returned
 * @param array $items Array of return items with quantities and reasons
 * @param string $notes Optional customer notes about the return
 * 
 * @return ReturnRequest The created return request instance
 * 
 * @throws \InvalidArgumentException If order does not belong to user
 * @throws \DomainException If order items are invalid
 * 
 * @example
 * $return = $service->createReturnRequest(
 *     orderId: 42,
 *     items: [
 *         ['order_item_id' => 10, 'quantity' => 2, 'reason' => 'Defective'],
 *     ],
 *     notes: 'Screen has dead pixels'
 * );
 */
public function createReturnRequest(
    int $orderId,
    array $items,
    string $notes = ''
): ReturnRequest {
    // Implementation
}

/**
 * Voucher model relationships
 * 
 * @property-read Collection<UserVoucher> $distributions User voucher distributions
 * @property-read Collection<VoucherUsage> $usages Voucher usage records
 */
class Voucher extends Model
{
    // Implementation
}
```

**Vue Component Documentation:**

```vue
<script setup lang="ts">
/**
 * ProductCard Component
 * 
 * Displays a single product with image, price, and add-to-cart button.
 * Used in product listing and search results.
 * 
 * @example
 * <ProductCard 
 *   :product="product"
 *   :show-rating="true"
 *   @add-to-cart="handleAddCart"
 * />
 */

interface Props {
  /** Product object containing id, name, price, image */
  product: Product;
  /** Show product rating if true (default: false) */
  showRating?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  showRating: false,
});

const emit = defineEmits<{
  /** Emitted when user clicks add to cart */
  addToCart: [quantity: number];
}>();
</script>
```

**Inline Comments - When to Use:**

```php
// ✅ DO: Explain WHY, not WHAT
// Check if voucher has reached usage limit to prevent overselling
if ($voucher->usage_count >= $voucher->usage_limit) {
    throw new VoucherExpiredException('Usage limit reached');
}

// ❌ DON'T: Obvious comments that restate the code
// Check if voucher is used
if ($userVoucher->is_used) { }

// ✅ DO: Clarify complex logic
// Refund calculation: item price × return quantity
// Note: Does not include tax/shipping per business rules
$refundAmount = $item->unit_price * $quantity;

// ✅ DO: Note important edge cases
// TODO: Handle concurrent returns of same item
// HACK: Temporary workaround for missing shipping API
// FIXME: This calculation doesn't account for tax
```

---

#### **Version Control & Git Workflow**

**Git Branching Strategy (GitFlow):**

```bash
# Main branches
main/                  # Production-ready code (stable releases)
develop/               # Development integration branch

# Feature branches
feature/order-tracking         # New feature development
feature/return-system          # Return management feature
feature/api-pagination         # API enhancement

# Bugfix branches
bugfix/cart-calculation        # Fix for identified bug
bugfix/voucher-validation      # Fix for voucher logic

# Hotfix branches (from main)
hotfix/payment-issue           # Critical production fix
hotfix/security-patch          # Security update

# Release branches
release/1.1.0                  # Release preparation
release/1.2.0                  # Bump version, prepare changelog
```

**Git Commit Message Standards:**

```bash
# Format: <type>(<scope>): <subject>
# Types: feat, fix, docs, style, refactor, test, chore

git commit -m "feat(return): add admin return approval workflow"
git commit -m "fix(voucher): prevent double redemption of vouchers"
git commit -m "docs(api): add endpoint documentation for products"
git commit -m "refactor(order): simplify order calculation logic"
git commit -m "test(cart): add tests for cart total calculation"
git commit -m "chore(deps): update Laravel to version 12.1.0"
```

**Pull Request/Code Review Process:**

1. Create feature branch from `develop`
2. Implement feature with tests
3. Create Pull Request with description
4. Code review by team members (minimum 2 approvals)
5. Run automated tests (GitHub Actions, CI/CD)
6. Merge to `develop` when approved
7. Merge `develop` → `main` for releases

**Repository Structure:**

```
.gitignore
├── Ignore vendor/          # PHP dependencies
├── Ignore node_modules/    # JavaScript dependencies
├── Ignore .env             # Environment variables
├── Ignore /storage         # Runtime files
├── Ignore /bootstrap/cache # Cache files
└── Ignore database.sqlite  # Development database

.github/
├── workflows/
│   ├── tests.yml           # Run tests on push/PR
│   ├── lint.yml            # Run linters (ESLint, Pint)
│   └── deploy.yml          # Deploy on merge to main
```

---

### 9.3 API Endpoints

Complete documentation of all TechStore API endpoints. Base URL: `http://localhost:8000/api/techstore/`

All endpoints require Bearer token authentication (except public endpoints) via `Authorization: Bearer {token}` header.

---

#### **Authentication Endpoints**

**Endpoint 1: User Registration**
```
POST /auth/register
```
- **Description:** Register a new customer account
- **Authentication:** None (public endpoint)
- **Request:**
  ```json
  {
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }
  ```
- **Response (201 Created):**
  ```json
  {
    "message": "User registered successfully",
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "created_at": "2025-12-22T10:30:00Z"
    },
    "token": "1|abc123xyz789..."
  }
  ```
- **Status Codes:**
  - `201 Created` - User registered successfully
  - `422 Unprocessable Entity` - Validation failed (invalid email, weak password)
  - `409 Conflict` - Email already registered

---

**Endpoint 2: User Login**
```
POST /auth/login
```
- **Description:** Authenticate user and receive API token
- **Authentication:** None (public endpoint)
- **Request:**
  ```json
  {
    "email": "john@example.com",
    "password": "password123"
  }
  ```
- **Response (200 OK):**
  ```json
  {
    "message": "Login successful",
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "token": "1|abc123xyz789..."
  }
  ```
- **Status Codes:**
  - `200 OK` - Login successful
  - `401 Unauthorized` - Invalid credentials
  - `404 Not Found` - User not found

---

**Endpoint 3: User Logout**
```
POST /auth/logout
```
- **Description:** Logout user and invalidate current token
- **Authentication:** Required (Bearer token)
- **Request:** Empty body
- **Response (200 OK):**
  ```json
  {
    "message": "Logged out successfully"
  }
  ```
- **Status Codes:**
  - `200 OK` - Logout successful
  - `401 Unauthorized` - Invalid or missing token

---

#### **Product Endpoints**

**Endpoint 4: List All Products**
```
GET /products
```
- **Description:** Retrieve paginated list of all products with filtering and sorting options
- **Authentication:** Optional (public endpoint)
- **Query Parameters:**
  ```
  ?page=1                    # Page number (default: 1)
  &per_page=10               # Items per page (default: 10)
  &search=laptop             # Search by product name
  &brand_id=5                # Filter by brand ID
  &sort=price                # Sort field (price, name, created_at)
  &order=asc                 # Sort order (asc, desc)
  &min_price=100             # Minimum price filter
  &max_price=5000            # Maximum price filter
  ```
- **Request:** None
- **Response (200 OK):**
  ```json
  {
    "data": [
      {
        "id": 1,
        "name": "MacBook Pro",
        "description": "Powerful laptop for professionals",
        "brand_id": 2,
        "brand_name": "Apple",
        "price": 1999.99,
        "stock_quantity": 50,
        "created_at": "2025-12-20T08:00:00Z",
        "updated_at": "2025-12-22T10:30:00Z"
      }
    ],
    "pagination": {
      "current_page": 1,
      "per_page": 10,
      "total": 150,
      "last_page": 15
    }
  }
  ```
- **Status Codes:**
  - `200 OK` - Products retrieved successfully
  - `400 Bad Request` - Invalid query parameters

---

**Endpoint 5: Get Product Details**
```
GET /products/{id}
```
- **Description:** Retrieve detailed information for a specific product
- **Authentication:** Optional (public endpoint)
- **URL Parameters:**
  - `id` (required) - Product ID
- **Request:** None
- **Response (200 OK):**
  ```json
  {
    "id": 1,
    "name": "MacBook Pro 16",
    "description": "Powerful laptop with M3 Max chip",
    "brand": {
      "id": 2,
      "name": "Apple",
      "description": "Apple Inc."
    },
    "price": 2499.99,
    "stock_quantity": 50,
    "created_at": "2025-12-20T08:00:00Z",
    "updated_at": "2025-12-22T10:30:00Z"
  }
  ```
- **Status Codes:**
  - `200 OK` - Product found
  - `404 Not Found` - Product does not exist

---

#### **Order Endpoints**

**Endpoint 6: List User Orders**
```
GET /orders
```
- **Description:** Retrieve paginated list of authenticated user's orders
- **Authentication:** Required (Bearer token)
- **Query Parameters:**
  ```
  ?page=1                    # Page number (default: 1)
  &per_page=10               # Items per page (default: 10)
  &status=completed          # Filter by status (pending, completed, cancelled)
  &sort=created_at           # Sort field (created_at, total_price)
  &order=desc                # Sort order (asc, desc)
  ```
- **Request:** None
- **Response (200 OK):**
  ```json
  {
    "data": [
      {
        "order_id": 101,
        "user_id": 1,
        "total_price": 2499.99,
        "payment_method": "card",
        "status": "completed",
        "order_date": "2025-12-20T14:30:00Z",
        "completed_at": "2025-12-21T09:00:00Z",
        "created_at": "2025-12-20T14:30:00Z"
      }
    ],
    "pagination": {
      "current_page": 1,
      "per_page": 10,
      "total": 25,
      "last_page": 3
    }
  }
  ```
- **Status Codes:**
  - `200 OK` - Orders retrieved successfully
  - `401 Unauthorized` - Invalid or missing token

---

**Endpoint 7: Get Order Details**
```
GET /orders/{orderId}
```
- **Description:** Retrieve full details of a specific order including items
- **Authentication:** Required (Bearer token)
- **URL Parameters:**
  - `orderId` (required) - Order ID
- **Request:** None
- **Response (200 OK):**
  ```json
  {
    "order_id": 101,
    "user_id": 1,
    "total_price": 2499.99,
    "payment_method": "card",
    "status": "completed",
    "order_date": "2025-12-20T14:30:00Z",
    "completed_at": "2025-12-21T09:00:00Z",
    "items": [
      {
        "order_item_id": 201,
        "product_id": 1,
        "product_name": "MacBook Pro 16",
        "quantity": 1,
        "unit_price": 2499.99,
        "subtotal": 2499.99
      }
    ],
    "shipping": {
      "address": "123 Main St",
      "city": "San Francisco",
      "state": "CA",
      "postal_code": "94102",
      "tracking_number": "1Z999AA10123456784",
      "status": "delivered"
    },
    "payment": {
      "id": 301,
      "amount": 2499.99,
      "payment_method": "credit_card",
      "status": "completed",
      "transaction_id": "txn_1234567890"
    }
  }
  ```
- **Status Codes:**
  - `200 OK` - Order found
  - `401 Unauthorized` - Invalid token
  - `403 Forbidden` - User does not own this order
  - `404 Not Found` - Order does not exist

---

**Endpoint 8: Get Order Statistics**
```
GET /orders-stats
```
- **Description:** Retrieve aggregate statistics about user's orders
- **Authentication:** Required (Bearer token)
- **Request:** None
- **Response (200 OK):**
  ```json
  {
    "total_orders": 25,
    "total_spent": 12450.50,
    "pending_orders": 3,
    "completed_orders": 20,
    "cancelled_orders": 2,
    "average_order_value": 498.02
  }
  ```
- **Status Codes:**
  - `200 OK` - Statistics retrieved successfully
  - `401 Unauthorized` - Invalid or missing token

---

#### **Return Request Endpoints**

**Endpoint 9: Create Return Request**
```
POST /return-requests
```
- **Description:** Submit a new return request for items from a completed order
- **Authentication:** Required (Bearer token)
- **Request:**
  ```json
  {
    "order_id": 101,
    "items": [
      {
        "order_item_id": 201,
        "quantity": 1,
        "reason": "Defective"
      }
    ],
    "notes": "Screen has dead pixels, product is unusable"
  }
  ```
- **Response (201 Created):**
  ```json
  {
    "id": 1,
    "user_id": 1,
    "order_id": 101,
    "status": "pending",
    "refund_amount": 2499.99,
    "notes": "Screen has dead pixels, product is unusable",
    "created_at": "2025-12-22T10:30:00Z"
  }
  ```
- **Status Codes:**
  - `201 Created` - Return request created successfully
  - `400 Bad Request` - Invalid request data
  - `401 Unauthorized` - Invalid or missing token
  - `403 Forbidden` - User does not own this order
  - `404 Not Found` - Order or item not found
  - `422 Unprocessable Entity` - Order not eligible for return (e.g., already completed)

---

**Endpoint 10: List User Return Requests**
```
GET /return-requests
```
- **Description:** Retrieve all return requests for authenticated user
- **Authentication:** Required (Bearer token)
- **Query Parameters:**
  ```
  ?page=1                    # Page number (default: 1)
  &per_page=10               # Items per page (default: 10)
  &status=pending            # Filter by status (pending, approved, rejected, refunded)
  ```
- **Request:** None
- **Response (200 OK):**
  ```json
  {
    "data": [
      {
        "id": 1,
        "order_id": 101,
        "status": "pending",
        "refund_amount": 2499.99,
        "created_at": "2025-12-22T10:30:00Z",
        "updated_at": "2025-12-22T10:30:00Z"
      }
    ],
    "pagination": {
      "current_page": 1,
      "per_page": 10,
      "total": 5,
      "last_page": 1
    }
  }
  ```
- **Status Codes:**
  - `200 OK` - Return requests retrieved successfully
  - `401 Unauthorized` - Invalid or missing token

---

**Endpoint 11: Get Return Request Details**
```
GET /return-requests/{id}
```
- **Description:** Retrieve full details of a specific return request
- **Authentication:** Required (Bearer token)
- **URL Parameters:**
  - `id` (required) - Return request ID
- **Request:** None
- **Response (200 OK):**
  ```json
  {
    "id": 1,
    "order_id": 101,
    "user_id": 1,
    "status": "pending",
    "refund_amount": 2499.99,
    "notes": "Screen has dead pixels",
    "created_at": "2025-12-22T10:30:00Z",
    "updated_at": "2025-12-22T10:30:00Z",
    "items": [
      {
        "return_request_item_id": 10,
        "order_item_id": 201,
        "product_id": 1,
        "product_name": "MacBook Pro 16",
        "quantity": 1,
        "refund_amount": 2499.99,
        "status": "pending",
        "reason": "Defective"
      }
    ]
  }
  ```
- **Status Codes:**
  - `200 OK` - Return request found
  - `401 Unauthorized` - Invalid token
  - `403 Forbidden` - User does not own this return request
  - `404 Not Found` - Return request not found

---

#### **Voucher Endpoints**

**Endpoint 12: Get User Vouchers**
```
GET /vouchers
```
- **Description:** Retrieve all vouchers available to authenticated user
- **Authentication:** Required (Bearer token)
- **Query Parameters:**
  ```
  ?page=1                    # Page number (default: 1)
  &per_page=10               # Items per page (default: 10)
  &status=active             # Filter by status (active, expired, used)
  ```
- **Request:** None
- **Response (200 OK):**
  ```json
  {
    "data": [
      {
        "id": 1,
        "code": "SUMMER20",
        "description": "Summer sale - 20% off",
        "discount_type": "percentage",
        "discount_value": 20,
        "start_date": "2025-06-01",
        "end_date": "2025-08-31",
        "usage_limit": 100,
        "is_used": false,
        "used_at": null
      }
    ],
    "pagination": {
      "current_page": 1,
      "per_page": 10,
      "total": 8,
      "last_page": 1
    }
  }
  ```
- **Status Codes:**
  - `200 OK` - Vouchers retrieved successfully
  - `401 Unauthorized` - Invalid or missing token

---

**Endpoint 13: Validate Voucher Code**
```
POST /vouchers/apply
```
- **Description:** Validate a voucher code and calculate discount amount
- **Authentication:** Required (Bearer token)
- **Request:**
  ```json
  {
    "code": "SUMMER20",
    "cart_total": 1000.00
  }
  ```
- **Response (200 OK):**
  ```json
  {
    "valid": true,
    "code": "SUMMER20",
    "description": "Summer sale - 20% off",
    "discount_type": "percentage",
    "discount_value": 20,
    "discount_amount": 200.00,
    "new_total": 800.00
  }
  ```
- **Error Response (400 Bad Request):**
  ```json
  {
    "valid": false,
    "error": "Voucher has expired or is not valid",
    "code": "VOUCHER_EXPIRED"
  }
  ```
- **Status Codes:**
  - `200 OK` - Voucher is valid
  - `400 Bad Request` - Voucher is invalid, expired, or already used
  - `401 Unauthorized` - Invalid or missing token
  - `404 Not Found` - Voucher code does not exist

---

#### **Admin Endpoints**

**Endpoint 14: Get All Orders (Admin)**
```
GET /admin/orders
```
- **Description:** Retrieve all orders in the system (admin only)
- **Authentication:** Required + Admin role (Bearer token + auth:admin middleware)
- **Query Parameters:**
  ```
  ?page=1                    # Page number (default: 1)
  &per_page=20               # Items per page (default: 20)
  &status=pending            # Filter by status
  &customer_id=5             # Filter by customer ID
  &sort=created_at           # Sort field
  ```
- **Request:** None
- **Response (200 OK):**
  ```json
  {
    "data": [
      {
        "order_id": 101,
        "customer_id": 1,
        "customer_name": "John Doe",
        "customer_email": "john@example.com",
        "total_price": 2499.99,
        "status": "pending",
        "order_date": "2025-12-20T14:30:00Z"
      }
    ],
    "pagination": {
      "current_page": 1,
      "per_page": 20,
      "total": 245,
      "last_page": 13
    }
  }
  ```
- **Status Codes:**
  - `200 OK` - Orders retrieved successfully
  - `401 Unauthorized` - Invalid or missing token
  - `403 Forbidden` - User is not an admin

---

**Endpoint 15: Get All Return Requests (Admin)**
```
GET /admin/return-requests
```
- **Description:** Retrieve all return requests for admin review (admin only)
- **Authentication:** Required + Admin role
- **Query Parameters:**
  ```
  ?page=1                    # Page number (default: 1)
  &status=pending            # Filter by status (pending, approved, rejected, refunded)
  &customer_id=5             # Filter by customer ID
  ```
- **Request:** None
- **Response (200 OK):**
  ```json
  {
    "data": [
      {
        "id": 1,
        "order_id": 101,
        "customer_id": 1,
        "customer_name": "John Doe",
        "customer_email": "john@example.com",
        "status": "pending",
        "refund_amount": 2499.99,
        "created_at": "2025-12-22T10:30:00Z"
      }
    ],
    "pagination": {
      "current_page": 1,
      "per_page": 10,
      "total": 15,
      "last_page": 2
    }
  }
  ```
- **Status Codes:**
  - `200 OK` - Return requests retrieved
  - `401 Unauthorized` - Invalid token
  - `403 Forbidden` - User is not an admin

---

**Endpoint 16: Approve Return Request (Admin)**
```
PATCH /admin/return-requests/{id}/approve
```
- **Description:** Approve a return request and process refund (admin only)
- **Authentication:** Required + Admin role
- **URL Parameters:**
  - `id` (required) - Return request ID
- **Request:**
  ```json
  {
    "notes": "Approved and refund initiated"
  }
  ```
- **Response (200 OK):**
  ```json
  {
    "id": 1,
    "status": "approved",
    "processed_by": 5,
    "processed_at": "2025-12-22T15:45:00Z",
    "refund_amount": 2499.99
  }
  ```
- **Status Codes:**
  - `200 OK` - Return approved successfully
  - `401 Unauthorized` - Invalid token
  - `403 Forbidden` - User is not an admin
  - `404 Not Found` - Return request not found
  - `422 Unprocessable Entity` - Cannot approve (already processed)

---

**Endpoint 17: Reject Return Request (Admin)**
```
PATCH /admin/return-requests/{id}/reject
```
- **Description:** Reject a return request with reason (admin only)
- **Authentication:** Required + Admin role
- **URL Parameters:**
  - `id` (required) - Return request ID
- **Request:**
  ```json
  {
    "reason": "Item condition does not meet return policy",
    "notes": "Customer opened package but did not use product"
  }
  ```
- **Response (200 OK):**
  ```json
  {
    "id": 1,
    "status": "rejected",
    "processed_by": 5,
    "processed_at": "2025-12-22T15:45:00Z",
    "rejection_reason": "Item condition does not meet return policy"
  }
  ```
- **Status Codes:**
  - `200 OK` - Return rejected successfully
  - `401 Unauthorized` - Invalid token
  - `403 Forbidden` - User is not an admin
  - `404 Not Found` - Return request not found
  - `422 Unprocessable Entity` - Cannot reject (already processed)

---

**Endpoint 18: Create Voucher (Admin)**
```
POST /admin/vouchers
```
- **Description:** Create a new promotional voucher (admin only)
- **Authentication:** Required + Admin role
- **Request:**
  ```json
  {
    "code": "NEWYEAR25",
    "description": "New Year Sale - 25% off",
    "discount_type": "percentage",
    "discount_value": 25,
    "start_date": "2026-01-01",
    "end_date": "2026-01-31",
    "usage_limit": 500,
    "status": "active"
  }
  ```
- **Response (201 Created):**
  ```json
  {
    "id": 5,
    "code": "NEWYEAR25",
    "description": "New Year Sale - 25% off",
    "discount_type": "percentage",
    "discount_value": 25,
    "start_date": "2026-01-01",
    "end_date": "2026-01-31",
    "usage_limit": 500,
    "usage_count": 0,
    "status": "active",
    "distributed_count": 150,
    "created_at": "2025-12-22T16:00:00Z"
  }
  ```
- **Status Codes:**
  - `201 Created` - Voucher created successfully
  - `400 Bad Request` - Invalid request data
  - `401 Unauthorized` - Invalid token
  - `403 Forbidden` - User is not an admin
  - `409 Conflict` - Code already exists

---

**Endpoint 19: Update Voucher (Admin)**
```
PATCH /admin/vouchers/{id}
```
- **Description:** Update voucher details (admin only)
- **Authentication:** Required + Admin role
- **URL Parameters:**
  - `id` (required) - Voucher ID
- **Request:**
  ```json
  {
    "description": "New Year Sale - Updated",
    "discount_value": 30,
    "usage_limit": 1000,
    "status": "active"
  }
  ```
- **Response (200 OK):**
  ```json
  {
    "id": 5,
    "code": "NEWYEAR25",
    "description": "New Year Sale - Updated",
    "discount_value": 30,
    "usage_limit": 1000,
    "status": "active",
    "updated_at": "2025-12-22T16:15:00Z"
  }
  ```
- **Status Codes:**
  - `200 OK` - Voucher updated successfully
  - `401 Unauthorized` - Invalid token
  - `403 Forbidden` - User is not an admin
  - `404 Not Found` - Voucher not found
  - `422 Unprocessable Entity` - Invalid update data

---

**Endpoint 20: Delete Voucher (Admin)**
```
DELETE /admin/vouchers/{id}
```
- **Description:** Delete a voucher (admin only)
- **Authentication:** Required + Admin role
- **URL Parameters:**
  - `id` (required) - Voucher ID
- **Request:** None
- **Response (200 OK):**
  ```json
  {
    "message": "Voucher deleted successfully",
    "id": 5
  }
  ```
- **Status Codes:**
  - `200 OK` - Voucher deleted successfully
  - `401 Unauthorized` - Invalid token
  - `403 Forbidden` - User is not an admin
  - `404 Not Found` - Voucher not found

---

#### **Error Response Format**

All error responses follow a consistent format:

```json
{
  "error": "Validation failed",
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  },
  "status_code": 422
}
```

**Common HTTP Status Codes:**

| Code | Meaning | Example |
|------|---------|---------|
| `200 OK` | Request succeeded | Successful GET/PATCH |
| `201 Created` | Resource created | POST successful |
| `400 Bad Request` | Invalid request data | Missing required fields |
| `401 Unauthorized` | Authentication failed | Invalid or missing token |
| `403 Forbidden` | Access denied | Non-admin accessing admin endpoint |
| `404 Not Found` | Resource not found | Order ID doesn't exist |
| `409 Conflict` | Conflict with existing data | Duplicate email |
| `422 Unprocessable Entity` | Validation failed | Invalid email format |
| `500 Server Error` | Server error | Database connection issue |

---

#### **API Testing with Postman**

A Postman Collection is included in the project root: `Techstore_Postman_Collection.json`

**To import:**
1. Open Postman
2. Click "Import" → Select `Techstore_Postman_Collection.json`
3. Select "Techstore_Postman_Environment.json" for environment variables
4. All endpoints pre-configured with example requests

**Example API Call:**

```bash
# Get all products
curl -X GET "http://localhost:8000/api/techstore/products?page=1&per_page=10" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json"

# Create return request (with authentication)
curl -X POST "http://localhost:8000/api/techstore/return-requests" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {token}" \
  -d '{
    "order_id": 101,
    "items": [{"order_item_id": 201, "quantity": 1, "reason": "Defective"}],
    "notes": "Screen has dead pixels"
  }'
```

---

## 10. References & Resources

### 10.1 Framework Documentation

**Laravel 12 Framework:**
- Official Documentation: https://laravel.com/docs/12.x
- Laravel Sanctum (API Authentication): https://laravel.com/docs/12.x/sanctum
- Eloquent ORM: https://laravel.com/docs/12.x/eloquent
- Database Migrations: https://laravel.com/docs/12.x/migrations
- Blade Templating: https://laravel.com/docs/12.x/blade
- Routing: https://laravel.com/docs/12.x/routing
- Middleware: https://laravel.com/docs/12.x/middleware

**Vue.js 3 & Nuxt.js 3:**
- Vue.js 3 Documentation: https://vuejs.org/
- Nuxt.js 3 Documentation: https://nuxt.com/docs
- Vue 3 Composition API: https://vuejs.org/guide/extras/composition-api-faq.html
- Pinia State Management: https://pinia.vuejs.org/
- Vue Router: https://router.vuejs.org/

**Frontend Tools:**
- Vite Build Tool: https://vitejs.dev/
- Tailwind CSS: https://tailwindcss.com/docs
- TypeScript: https://www.typescriptlang.org/docs/

### 10.2 Database & ORM

**Eloquent ORM:**
- Relationships: https://laravel.com/docs/12.x/eloquent-relationships
- Queries & Scopes: https://laravel.com/docs/12.x/eloquent#query-scopes
- Accessors & Mutators: https://laravel.com/docs/12.x/eloquent-mutators

**Database Design:**
- MySQL 8.0 Documentation: https://dev.mysql.com/doc/refman/8.0/en/
- PostgreSQL Documentation: https://www.postgresql.org/docs/
- SQLite Documentation: https://www.sqlite.org/docs.html

### 10.3 Authentication & Security

**Laravel Security:**
- Authentication: https://laravel.com/docs/12.x/authentication
- Authorization & Policies: https://laravel.com/docs/12.x/authorization
- Hashing & Encryption: https://laravel.com/docs/12.x/hashing
- CSRF Protection: https://laravel.com/docs/12.x/csrf

**Security Best Practices:**
- OWASP Top 10: https://owasp.org/www-project-top-ten/
- Laravel Security Guidelines: https://laravel.com/docs/12.x/structure

### 10.4 API Development

**RESTful API Design:**
- JSON:API Specification: https://jsonapi.org/
- HTTP Status Codes: https://www.rfc-editor.org/rfc/rfc7231#section-6
- API Versioning Best Practices: https://restfulapi.net/versioning/

**Testing Tools:**
- Postman: https://www.postman.com/
- Insomnia: https://insomnia.rest/
- cURL Documentation: https://curl.se/docs/

### 10.5 Development Tools

**Version Control:**
- Git Documentation: https://git-scm.com/doc
- GitHub Workflows: https://docs.github.com/en/actions
- GitFlow Workflow: https://nvie.com/posts/a-successful-git-branching-model/

**Code Quality:**
- Laravel Pint: https://github.com/laravel/pint
- ESLint: https://eslint.org/docs/latest/
- Prettier: https://prettier.io/docs/en/index.html
- PHPUnit Testing: https://phpunit.de/documentation.html

**Package Managers:**
- Composer (PHP): https://getcomposer.org/doc/
- npm (Node.js): https://docs.npmjs.com/
- Yarn: https://classic.yarnpkg.com/en/docs/

### 10.6 DevOps & Deployment

**Server Technologies:**
- Docker: https://docs.docker.com/
- Docker Compose: https://docs.docker.com/compose/
- Nginx Web Server: https://nginx.org/en/docs/
- Apache Web Server: https://httpd.apache.org/docs/

**Continuous Integration/Deployment:**
- GitHub Actions: https://docs.github.com/en/actions
- Laravel Forge: https://forge.laravel.com/
- Laravel Vapor: https://vapor.laravel.com/

### 10.7 Additional Learning Resources

**Articles & Tutorials:**
- "Building E-Commerce Platforms with Laravel" - Laravel Blog
- "Modern Vue.js Development" - Vue.js Official Guide
- "Securing Laravel Applications" - Laravel Security Documentation
- "Database Design Patterns" - Database Design Best Practices
- "RESTful API Design Guidelines" - REST API Best Practices

**Communities & Support:**
- Laravel Community Forums: https://laracasts.com/discuss
- Stack Overflow: https://stackoverflow.com/questions/tagged/laravel
- Reddit Communities: r/laravel, r/vuejs, r/PHP
- GitHub Issues & Discussions

### 10.8 Tools & Services Used in Project

**Development Environment:**
- PHP 8.2 with Xdebug
- MySQL 8.0 / SQLite
- Node.js 18+ with npm 9+
- Visual Studio Code
- Laravel Sail (Docker)

**Testing & Quality:**
- PHPUnit - Unit & Feature Testing
- Jest - JavaScript Testing (optional)
- Laravel Pint - Code Formatting
- ESLint - JavaScript Linting
- GitHub Actions - CI/CD

**Monitoring & Analytics:**
- Laravel Telescope (Development debugging)
- Laravel Tinker (Interactive REPL)
- Browser DevTools (Frontend debugging)
- Application Logs (storage/logs/laravel.log)

---

## 11. Appendix

### 11.1 Additional Diagrams

#### **Diagram 1: User Authentication & Authorization Flow**

```
┌─────────────────────────────────────────────────────────────────────┐
│                   USER AUTHENTICATION FLOW                          │
└─────────────────────────────────────────────────────────────────────┘

CUSTOMER LOGIN/REGISTRATION:
┌──────────────┐
│ User visits  │
│ /register or │
│ /login       │
└──────┬───────┘
       ↓
┌──────────────────────────────────────┐
│ Frontend form submission             │
│ (email, password)                    │
└──────┬───────────────────────────────┘
       ↓
┌──────────────────────────────────────┐
│ Laravel Controller receives request  │
│ (AuthController)                     │
└──────┬───────────────────────────────┘
       ↓
┌──────────────────────────────────────┐
│ Validate input data                  │
│ (email format, password strength)    │
└──────┬───────────────────────────────┘
       ↓
    [Registration OR Login branch]
       │
       ├─→ REGISTRATION PATH:
       │   ├─ Hash password with bcrypt
       │   ├─ Check email uniqueness
       │   ├─ Create User record
       │   ├─ Generate Sanctum token
       │   └─ Return token + user data
       │
       └─→ LOGIN PATH:
           ├─ Find user by email
           ├─ Verify password hash
           ├─ Generate Sanctum API token
           ├─ Store token in database
           └─ Return token + user data
       ↓
┌──────────────────────────────────────┐
│ Frontend stores token (localStorage) │
│ or (secure HTTP-only cookie)         │
└──────┬───────────────────────────────┘
       ↓
┌──────────────────────────────────────┐
│ User authenticated ✓                 │
│ Can access protected routes          │
└──────────────────────────────────────┘

API REQUEST WITH AUTHENTICATION:
┌──────────────────────────────┐
│ Frontend includes Bearer      │
│ Authorization header with     │
│ token                         │
└──────┬───────────────────────┘
       ↓
┌──────────────────────────────────────┐
│ Sanctum Middleware intercepts        │
│ (auth:sanctum)                       │
└──────┬───────────────────────────────┘
       ↓
┌──────────────────────────────────────┐
│ Verify token validity                │
│ ├─ Token exists in DB?               │
│ ├─ Token expired?                    │
│ └─ Token matches user?               │
└──────┬───────────────────────────────┘
       ↓
    [Valid OR Invalid]
       │
       ├─→ VALID:
       │   ├─ Authenticate user
       │   ├─ Grant request access
       │   └─ Proceed to controller
       │
       └─→ INVALID:
           ├─ Return 401 Unauthorized
           └─ Redirect to login
       ↓
┌──────────────────────────────────────┐
│ Controller executes with user context│
│ Access to $user data                 │
└──────────────────────────────────────┘

ADMIN AUTHENTICATION:
┌──────────────────────────────────────┐
│ Similar flow but with auth:admin     │
│ middleware                           │
└──────┬───────────────────────────────┘
       ↓
┌──────────────────────────────────────┐
│ Verify user is admin                 │
│ (check role/is_admin flag)           │
└──────┬───────────────────────────────┘
       ↓
    [Admin OR Not Admin]
       │
       ├─→ ADMIN: Grant admin access
       │
       └─→ NOT ADMIN: Return 403 Forbidden
       ↓
┌──────────────────────────────────────┐
│ Admin can manage orders, returns,    │
│ vouchers, users, etc.                │
└──────────────────────────────────────┘
```

---

#### **Diagram 2: Return Request Processing Workflow**

```
┌─────────────────────────────────────────────────────────────────────┐
│                   RETURN REQUEST WORKFLOW                           │
└─────────────────────────────────────────────────────────────────────┘

CUSTOMER INITIATES RETURN:
┌─────────────────────────────────────────┐
│ Customer views order history            │
│ Selects order to return                 │
└──────┬──────────────────────────────────┘
       ↓
┌─────────────────────────────────────────┐
│ Customer fills return form:             │
│ • Select items from order               │
│ • Choose return reason                  │
│ • Add optional notes                    │
│ • Confirm quantities                    │
└──────┬──────────────────────────────────┘
       ↓
┌─────────────────────────────────────────┐
│ System validates:                       │
│ • Items belong to customer's order      │
│ • Order is eligible for return          │
│ • Quantities are valid                  │
│ • Reason provided                       │
└──────┬──────────────────────────────────┘
       ↓
┌─────────────────────────────────────────┐
│ System calculates refund amount:        │
│ Total = Σ(item_price × qty_returning)  │
│ (Does NOT include tax/shipping)         │
└──────┬──────────────────────────────────┘
       ↓
┌─────────────────────────────────────────┐
│ Create ReturnRequest record             │
│ Status: PENDING                         │
│ Create ReturnRequestItem records        │
│ for each item being returned            │
└──────┬──────────────────────────────────┘
       ↓
┌─────────────────────────────────────────┐
│ Send confirmation email to customer     │
│ with return details & tracking info     │
└──────┬──────────────────────────────────┘
       ↓

CUSTOMER DASHBOARD:
┌─────────────────────────────────────────┐
│ View return status in dashboard         │
│ Status: PENDING → Waiting for admin     │
└──────┬──────────────────────────────────┘
       ↓

ADMIN REVIEWS RETURN:
┌─────────────────────────────────────────┐
│ Admin views returns dashboard           │
│ Filter by status (pending, approved,    │
│ rejected, refunded)                     │
└──────┬──────────────────────────────────┘
       ↓
┌─────────────────────────────────────────┐
│ Admin clicks on pending return          │
│ Views:                                  │
│ • Customer information                  │
│ • Original order details                │
│ • Items being returned with reasons     │
│ • Calculated refund amount              │
│ • Customer notes                        │
└──────┬──────────────────────────────────┘
       ↓

ADMIN MAKES DECISION:
       │
       ├─→ APPROVE RETURN:
       │   ├─ Click "Approve" button
       │   ├─ Add optional notes
       │   ├─ System updates status → APPROVED
       │   ├─ Record admin ID & timestamp
       │   ├─ Queue refund processing
       │   ├─ Send approval email to customer
       │   └─ Update statistics
       │
       └─→ REJECT RETURN:
           ├─ Click "Reject" button
           ├─ Provide rejection reason
           ├─ System updates status → REJECTED
           ├─ Record reason & admin
           ├─ Send rejection email to customer
           └─ Update statistics
       ↓
┌─────────────────────────────────────────┐
│ Status change logged in database        │
│ Audit trail created with timestamp      │
└──────┬──────────────────────────────────┘
       ↓

CUSTOMER NOTIFIED:
┌─────────────────────────────────────────┐
│ Email received (approved or rejected)   │
│ Customer dashboard updated              │
│ Return status: APPROVED or REJECTED     │
└──────┬──────────────────────────────────┘
       ↓

IF APPROVED:
┌─────────────────────────────────────────┐
│ Return status progresses:               │
│ APPROVED → REFUND_INITIATED → REFUNDED  │
│ (Manual or automatic based on payment)  │
└──────┬──────────────────────────────────┘
       ↓
┌─────────────────────────────────────────┐
│ Customer receives refund on original    │
│ payment method                          │
│ Status: REFUNDED                        │
└──────────────────────────────────────────┘
```

---

#### **Diagram 3: Voucher Distribution & Usage Flow**

```
┌─────────────────────────────────────────────────────────────────────┐
│                   VOUCHER DISTRIBUTION FLOW                         │
└─────────────────────────────────────────────────────────────────────┘

ADMIN CREATES VOUCHER:
┌──────────────────────────────────────┐
│ Admin clicks "Create Voucher"         │
│ in Voucher Management                 │
└──────┬───────────────────────────────┘
       ↓
┌──────────────────────────────────────┐
│ Admin fills voucher form:             │
│ • Unique code (SUMMER20)              │
│ • Description                         │
│ • Discount type (% or fixed)          │
│ • Discount value                      │
│ • Start & end dates                   │
│ • Usage limit                         │
│ • Status (active/inactive)            │
└──────┬───────────────────────────────┘
       ↓
┌──────────────────────────────────────┐
│ System validates:                    │
│ • Code uniqueness                    │
│ • Date range validity                │
│ • Discount value range               │
│ • Status allowed                     │
└──────┬───────────────────────────────┘
       ↓
┌──────────────────────────────────────┐
│ Create Voucher record in database    │
│ Code, type, value, dates, limits     │
└──────┬───────────────────────────────┘
       ↓

AUTO-DISTRIBUTION TO USERS:
┌──────────────────────────────────────┐
│ Get all active users from database   │
│ (excluding test/disabled accounts)   │
└──────┬───────────────────────────────┘
       ↓
┌──────────────────────────────────────┐
│ Create UserVoucher records for each  │
│ user via batch insert                │
│ (Efficient bulk operation)           │
└──────┬───────────────────────────────┘
       ↓
┌──────────────────────────────────────┐
│ Store distributed count              │
│ Example: "150 users received code"   │
└──────┬───────────────────────────────┘
       ↓
┌──────────────────────────────────────┐
│ Display success message to admin     │
│ Show distribution statistics         │
└──────┬───────────────────────────────┘
       ↓

CUSTOMER VIEWS VOUCHERS:
┌──────────────────────────────────────┐
│ Customer accesses dashboard          │
│ or voucher section                   │
└──────┬───────────────────────────────┘
       ↓
┌──────────────────────────────────────┐
│ System queries user_vouchers table   │
│ Shows all vouchers assigned to user  │
└──────┬───────────────────────────────┘
       ↓
┌──────────────────────────────────────┐
│ Display to customer:                 │
│ • Voucher code                       │
│ • Discount amount                    │
│ • Expiration date                    │
│ • Usage status (unused/used)         │
└──────┬───────────────────────────────┘
       ↓

CUSTOMER APPLIES VOUCHER AT CHECKOUT:
┌──────────────────────────────────────┐
│ During checkout process              │
│ Customer enters voucher code         │
└──────┬───────────────────────────────┘
       ↓
┌──────────────────────────────────────┐
│ System validates voucher:            │
│ • Code exists?                       │
│ • Within date range?                 │
│ • Usage limit not reached?           │
│ • User hasn't used it before?        │
│ • Status is active?                  │
└──────┬───────────────────────────────┘
       ↓
    [Valid OR Invalid]
       │
       ├─→ VALID:
       │   ├─ Calculate discount
       │   │  (if percentage: cart × %)
       │   │  (if fixed: subtract amount)
       │   ├─ Update order total
       │   ├─ Display new total
       │   └─ Show discount savings
       │
       └─→ INVALID:
           ├─ Show error message
           ├─ Reason (expired, used, etc)
           └─ Allow customer to try again
       ↓
┌──────────────────────────────────────┐
│ Customer completes purchase          │
│ Order created with discount applied  │
└──────┬───────────────────────────────┘
       ↓
┌──────────────────────────────────────┐
│ Create VoucherUsage record:          │
│ • Voucher ID                         │
│ • User ID                            │
│ • Order ID                           │
│ • Timestamp                          │
└──────┬───────────────────────────────┘
       ↓
┌──────────────────────────────────────┐
│ Mark UserVoucher as used             │
│ Set is_used = true                   │
│ Record usage timestamp               │
└──────┬───────────────────────────────┘
       ↓
┌──────────────────────────────────────┐
│ Increment voucher usage_count        │
│ Check against usage_limit            │
└──────┬───────────────────────────────┘
       ↓

ADMIN VIEWS VOUCHER STATISTICS:
┌──────────────────────────────────────┐
│ Dashboard shows:                     │
│ • Total distributed: 150             │
│ • Times used: 45                     │
│ • Remaining uses: 55                 │
│ • Redemption rate: 30%               │
└──────┬───────────────────────────────┘
       ↓
┌──────────────────────────────────────┐
│ Admin can edit or redistribute       │
│ Create new batch for new users       │
└──────────────────────────────────────┘
```

---

#### **Diagram 4: System Architecture & Deployment**

```
┌─────────────────────────────────────────────────────────────────────┐
│              TECHSTORE SYSTEM ARCHITECTURE                          │
└─────────────────────────────────────────────────────────────────────┘

PRODUCTION ENVIRONMENT:

┌─────────────────────────────────────────────────────────────────┐
│                        CLIENT LAYER                            │
├─────────────────────────────────────────────────────────────────┤
│  Browser                              Mobile App                │
│  ├─ Nuxt.js Frontend                 ├─ React Native (Future)  │
│  ├─ Vue.js Components                └─ Native App API Calls   │
│  └─ Vuex/Pinia Store                                           │
└────────────────┬──────────────────────────────────────────────┘
                 │ HTTPS/TLS
                 │ JSON API Calls
                 ↓
┌─────────────────────────────────────────────────────────────────┐
│                    LOAD BALANCER / REVERSE PROXY               │
│  (Nginx / Cloud Load Balancer)                                 │
│  • SSL/TLS Termination                                         │
│  • Request Distribution                                        │
│  • Static Asset Caching                                        │
│  • Rate Limiting                                               │
└────────────────┬──────────────────────────────────────────────┘
                 │
        ┌────────┴────────┐
        ↓                 ↓
┌──────────────┐  ┌──────────────┐  ┌──────────────┐
│ App Server 1 │  │ App Server 2 │  │ App Server N │
│ (Laravel)    │  │ (Laravel)    │  │ (Laravel)    │
│ Port 8000    │  │ Port 8000    │  │ Port 8000    │
└────────┬─────┘  └────────┬─────┘  └────────┬─────┘
         │                 │                 │
         └─────────────────┼─────────────────┘
                           │
                           ↓
         ┌──────────────────────────────────┐
         │     SHARED DATABASE              │
         │  MySQL 8.0 / PostgreSQL          │
         │  ├─ users table                  │
         │  ├─ products table               │
         │  ├─ orders table                 │
         │  ├─ return_requests table        │
         │  ├─ vouchers table               │
         │  └─ ... (all tables)             │
         │                                  │
         │  Primary-Replica Setup           │
         │  ├─ Write to Primary             │
         │  └─ Read from Replicas           │
         └──────────────────────────────────┘
                           │
         ┌─────────────────┼─────────────────┐
         ↓                 ↓                 ↓
    ┌─────────┐   ┌──────────────┐  ┌───────────┐
    │  CACHE  │   │ FILE STORAGE │  │   QUEUE   │
    │  Redis  │   │  AWS S3 / GCS│  │  Redis    │
    │         │   │              │  │  RabbitMQ │
    │ Sessions│   │  • Product   │  │           │
    │ Queries │   │    images    │  │ Jobs:     │
    │ Views   │   │  • Documents │  │ • Send    │
    └─────────┘   │  • Uploads   │  │   emails  │
                  └──────────────┘  │ • Reports │
                                    └───────────┘

SUPPORTING SERVICES:
┌────────────────────────────────────────────────┐
│  Email Service (SMTP)                         │
│  ├─ SendGrid / Mailgun / AWS SES              │
│  └─ Notifications, confirmations, receipts    │
├────────────────────────────────────────────────┤
│  Payment Gateway                               │
│  ├─ Stripe / PayPal / GCash API               │
│  └─ Process payments securely                  │
├────────────────────────────────────────────────┤
│  Logging & Monitoring                          │
│  ├─ ELK Stack / Datadog / New Relic           │
│  ├─ Application logs                           │
│  ├─ Performance metrics                        │
│  └─ Error tracking                             │
├────────────────────────────────────────────────┤
│  CI/CD Pipeline                                │
│  ├─ GitHub Actions                             │
│  ├─ Run tests                                  │
│  ├─ Linting & code quality                     │
│  └─ Automatic deployment                       │
└────────────────────────────────────────────────┘

DEVELOPMENT ENVIRONMENT:

Developer Machine (Laptop)
├─ Docker Desktop
│  └─ Laravel Sail container
│     ├─ PHP 8.2
│     ├─ Laravel 12
│     ├─ SQLite (database.sqlite)
│     ├─ Redis (caching)
│     └─ Nginx (reverse proxy)
│
├─ Node.js 18+ (Host machine)
│  ├─ npm / yarn package manager
│  └─ Nuxt development server (HMR)
│
├─ Code Editor (VS Code)
│  └─ Laravel IDE Helper
│  └─ ESLint & Prettier extensions
│
└─ Git & GitHub
   └─ Version control & collaboration

DEPLOYMENT FLOW:

Developer
  ↓
  └─→ git push to feature branch
      ↓
      └─→ GitHub Actions triggered
          ├─ Run tests (PHPUnit)
          ├─ Lint code (Pint, ESLint)
          ├─ Build artifacts
          └─ Comment on PR
      ↓
      └─→ Code Review
          ├─ Team members review
          ├─ Approval required
          └─ Request changes if needed
      ↓
      └─→ Merge to develop branch
          ├─ GitHub Actions re-runs
          └─ Automatic deployment to staging
      ↓
      └─→ Staging Environment Testing
          ├─ Manual QA testing
          ├─ Integration testing
          └─ Performance testing
      ↓
      └─→ Merge to main branch
          ├─ Final tests run
          ├─ Build production artifacts
          └─ Automatic deployment to production
      ↓
      └─→ Production Live
          ├─ Monitor health
          ├─ Collect metrics
          └─ Alert on errors
```

---

#### **Diagram 5: Data Flow - Order to Delivery**

```
┌─────────────────────────────────────────────────────────────────────┐
│              ORDER TO DELIVERY DATA FLOW                            │
└─────────────────────────────────────────────────────────────────────┘

STEP 1: CHECKOUT
User adds items to cart → Frontend sends POST /orders
         ↓
    Order Controller validates data
         ↓
    Database transaction begins
    ├─ Create Order record
    │  ├─ user_id, total_price, payment_method
    │  ├─ status = 'pending'
    │  └─ order_date = now()
    │
    ├─ Create OrderItem records (for each item)
    │  ├─ order_id, product_id
    │  ├─ quantity, unit_price
    │  └─ subtotal = quantity × price
    │
    ├─ Update product stock
    │  └─ stock_quantity -= ordered_qty
    │
    ├─ Apply voucher (if used)
    │  ├─ Calculate discount
    │  ├─ Update order total
    │  └─ Mark UserVoucher as used
    │
    ├─ Create Payment record
    │  ├─ order_id, amount
    │  ├─ payment_method, status = 'pending'
    │  └─ transaction_id from gateway
    │
    ├─ Create Shipping record
    │  ├─ order_id, address
    │  ├─ city, state, postal_code
    │  └─ status = 'pending'
    │
    └─ COMMIT transaction ✓
         ↓

STEP 2: PAYMENT PROCESSING
If payment fails:
    ├─ ROLLBACK transaction
    ├─ Return error to customer
    ├─ Send error email
    └─ Order not created

If payment succeeds:
    ├─ Update Payment status = 'completed'
    ├─ Update Order status = 'processing'
    ├─ Enqueue jobs (background queue)
    │  ├─ SendOrderConfirmationEmail job
    │  ├─ UpdateInventoryJob
    │  └─ NotifyWarehouseJob
    ├─ Send confirmation to customer
    └─ Confirmation visible in dashboard
         ↓

STEP 3: FULFILLMENT
Warehouse receives order notification:
    ├─ Pick items from shelves
    ├─ Pack order
    ├─ Generate shipping label
    └─ Scan items into tracking system
         ↓
    Update Shipping record:
    ├─ tracking_number = 1Z999AA...
    ├─ status = 'shipped'
    ├─ carrier = FedEx/UPS/DHL
    └─ estimated_delivery = Date+5days
         ↓
    Update Order status = 'shipped'
         ↓
    Send shipping notification to customer:
    ├─ Email with tracking link
    ├─ SMS (optional)
    └─ In-app notification
         ↓

STEP 4: DELIVERY
Customer tracks shipment via carrier:
    ├─ In transit
    ├─ Out for delivery
    └─ Delivered
         ↓
    Backend system periodically polls
    carrier API for status updates
    (or receives webhook notifications)
         ↓
    Update Shipping status = 'delivered'
    Update Order status = 'completed'
    Update completed_at timestamp
         ↓
    Notify customer:
    ├─ Delivery confirmation email
    ├─ In-app notification
    └─ Ask for review (optional)
         ↓

STEP 5: POST-DELIVERY
Customer receives order:
    ├─ Can view in order history
    ├─ Can request return (7-30 days)
    ├─ Can leave review
    └─ Can download invoice
         ↓
    If customer requests return:
    └─→ Go to Return Request Flow
         ↓

STEP 6: ANALYTICS & REPORTING
Order data aggregated for insights:
    ├─ Dashboard shows:
    │  ├─ Total orders
    │  ├─ Total revenue
    │  ├─ Orders by status
    │  ├─ Top products
    │  └─ Sales by date
    │
    ├─ Inventory management:
    │  ├─ Stock levels updated
    │  ├─ Low stock alerts
    │  └─ Reorder suggestions
    │
    └─ Customer insights:
       ├─ Purchase history
       ├─ Lifetime value
       ├─ Repeat purchases
       └─ Return rate by product
```

---

#### **Diagram 6: Database Relationship Map (Simplified)**

```
┌─────────────────────────────────────────────────────────────────────┐
│           SIMPLIFIED DATABASE RELATIONSHIP MAP                      │
└─────────────────────────────────────────────────────────────────────┘

┌──────────┐
│  USERS   │  (Customer authentication)
├──────────┤
│ id (PK)  │
│ email    │
│ name     │
│ password │
└─────┬────┘
      │ 1:N
      ├──→ CUSTOMERS (Customer profile)
      ├──→ ORDERS (Order records)
      ├──→ RETURN_REQUESTS (Return requests)
      ├──→ USER_VOUCHERS (Vouchers assigned)
      └──→ VOUCHER_USAGE (Voucher usage history)

┌────────────┐
│ ADMINS     │  (Admin authentication)
├────────────┤
│ admin_id   │
│ email      │
│ name       │
│ password   │
└─────┬──────┘
      │ 1:N
      └──→ RETURN_REQUESTS (Processed by)
      └──→ VOUCHERS (Created by)

┌──────────────┐
│ PRODUCTS     │  (Product catalog)
├──────────────┤
│ id (PK)      │
│ name         │
│ brand_id (FK)│◄────┐
│ price        │     │ 1:N
│ stock_qty    │     │
└──────┬───────┘     │
       │             │
       │ 1:N    ┌────────┐
       ├───────→│ BRANDS │
       │        └────────┘
       │
       │ 1:N
       └───→ ORDER_ITEMS
       └───→ RETURN_REQUEST_ITEMS

┌──────────┐
│ ORDERS   │  (Customer orders)
├──────────┤
│ order_id │
│ user_id  │◄──────┐
│ total    │       │ N:1
│ status   │       │
└─────┬────┘   ┌───────┐
      │ 1:N    │ USERS │
      │        └───────┘
      ├───→ ORDER_ITEMS
      ├───→ PAYMENTS
      ├───→ SHIPPING
      ├───→ RETURN_REQUESTS
      └───→ VOUCHER_USAGE

┌──────────────┐
│ ORDER_ITEMS  │  (Items in order)
├──────────────┤
│ order_item_id│
│ order_id (FK)│◄────────┐
│ product_id   │         │ N:1
│ quantity     │    ┌────────┐
│ unit_price   │    │ ORDERS │
└──────────────┘    └────────┘

┌─────────────────┐
│ RETURN_REQUESTS │  (Return workflow)
├─────────────────┤
│ id              │
│ user_id (FK)    │◄──┐
│ order_id (FK)   │   │ N:1
│ status          │
│ refund_amount   │
│ processed_by    │
└────────┬────────┘
         │ 1:N
         └───→ RETURN_REQUEST_ITEMS

┌──────────────────────┐
│ RETURN_REQUEST_ITEMS │  (Items being returned)
├──────────────────────┤
│ return_request_id(FK)│
│ order_item_id (FK)   │
│ quantity             │
│ refund_amount        │
└──────────────────────┘

┌──────────┐
│ VOUCHERS │  (Promotional codes)
├──────────┤
│ voucher_id│
│ code      │
│ discount_ │
│  type     │ 1:N
│ value     ├────→ USER_VOUCHERS
└─────┬────┘      └────→ VOUCHER_USAGE

┌────────────────┐
│ USER_VOUCHERS  │  (Distribution tracking)
├────────────────┤
│ user_id (FK)   │◄──┐
│ voucher_id (FK)│   │ N:1
│ is_used        │
│ used_at        │
└────────────────┘

┌─────────────────┐
│ VOUCHER_USAGE   │  (Usage history)
├─────────────────┤
│ voucher_id (FK) │
│ user_id (FK)    │
│ order_id (FK)   │
│ used_at         │
└─────────────────┘

┌──────────┐
│ PAYMENTS │  (Payment records)
├──────────┤
│ id       │
│ order_id │◄──┐
│ amount   │   │ N:1
│ method   │   │
│ status   │   │
└──────────┘  ORDERS

┌──────────┐
│ SHIPPING │  (Delivery tracking)
├──────────┤
│ id       │
│ order_id │◄──┐
│ address  │   │ N:1
│ status   │   │
│ tracking │   │
└──────────┘  ORDERS

LEGEND:
─→  One-to-Many relationship (1:N)
◄──  Many-to-One relationship (N:1)
(FK) Foreign Key reference
(PK) Primary Key
```

---

#### **Diagram 7: Comprehensive System Architecture Overview**

```
┌─────────────────────────────────────────────────────────────────────┐
│         TECHSTORE E-COMMERCE PLATFORM - SYSTEM ARCHITECTURE         │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────┐
│                         CLIENT/PRESENTATION LAYER                       │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│   WEB BROWSER              MOBILE APP             ADMIN DASHBOARD      │
│   ┌──────────────┐         ┌──────────────┐      ┌──────────────┐    │
│   │ Nuxt.js 3.x  │         │ React Native │      │ Nuxt Admin   │    │
│   │ • Pages      │         │ (Future)     │      │ • Pages      │    │
│   │ • Components │         │ • Navigation │      │ • Components │    │
│   │ • Routing    │         │ • APIs       │      │ • Routing    │    │
│   └──────┬───────┘         └──────┬───────┘      └──────┬───────┘    │
│          │                        │                      │             │
│   ┌──────────────┐         ┌──────────────┐      ┌──────────────┐    │
│   │ Vue.js 3.x   │         │ Redux Store  │      │ Pinia Store  │    │
│   │ • Reactive   │         │ • App State  │      │ • Admin State│    │
│   │ • Templates  │         │ • Caching    │      │ • Cache      │    │
│   └──────┬───────┘         └──────┬───────┘      └──────┬───────┘    │
│          │                        │                      │             │
│   ┌──────────────┐         ┌──────────────┐      ┌──────────────┐    │
│   │ Tailwind CSS │         │ Native API   │      │ Tailwind CSS │    │
│   │ • Styling    │         │ • Direct     │      │ • Admin UI   │    │
│   │ • Responsive │         │   Calls      │      │ • Responsive │    │
│   └──────────────┘         └──────────────┘      └──────────────┘    │
│                                                                         │
└────────────┬──────────────────────────┬──────────────────────┬────────┘
             │                          │                      │
             │ HTTPS/TLS               │ HTTPS/TLS            │ HTTPS/TLS
             │ (Port 443)              │ (Port 443)           │ (Port 443)
             ↓                         ↓                      ↓

┌─────────────────────────────────────────────────────────────────────────┐
│                    API GATEWAY / LOAD BALANCER LAYER                    │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│                     ┌──────────────────────────┐                       │
│                     │  Nginx Reverse Proxy     │                       │
│                     │  ├─ SSL/TLS Termination  │                       │
│                     │  ├─ Request Routing      │                       │
│                     │  ├─ Rate Limiting        │                       │
│                     │  ├─ Caching              │                       │
│                     │  └─ Load Balancing       │                       │
│                     └───────────┬──────────────┘                       │
│                                 │                                      │
└─────────────────────────────────┼──────────────────────────────────────┘
                                  │
                ┌─────────────────┼─────────────────┐
                │                 │                 │
                ↓                 ↓                 ↓

┌──────────────────┐  ┌──────────────────┐  ┌──────────────────┐
│  APP SERVER 1    │  │  APP SERVER 2    │  │  APP SERVER N    │
│  (Laravel 12)    │  │  (Laravel 12)    │  │  (Laravel 12)    │
└──────────────────┘  └──────────────────┘  └──────────────────┘
(Horizontal Scaling)

┌─────────────────────────────────────────────────────────────────────────┐
│                      APPLICATION / BUSINESS LOGIC LAYER                 │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  AUTHENTICATION CONTROLLERS    PRODUCT CONTROLLERS                     │
│  ├─ RegisterController         ├─ ProductController                   │
│  ├─ LoginController            ├─ BrandController                     │
│  ├─ LogoutController           └─ CategoryController                  │
│  └─ TokenRefreshController                                            │
│                               ORDER CONTROLLERS                       │
│  VOUCHER CONTROLLERS          ├─ OrderController                      │
│  ├─ VoucherController         ├─ OrderItemController                  │
│  ├─ AdminVoucherController    ├─ PaymentController                    │
│  └─ VoucherDistributionCmnd   └─ ShippingController                   │
│                                                                         │
│  RETURN CONTROLLERS           DASHBOARD CONTROLLERS                   │
│  ├─ ReturnRequestController   ├─ AdminDashboardController             │
│  ├─ AdminReturnController     ├─ CustomerDashboardController          │
│  └─ ReturnItemController      └─ AnalyticsController                  │
│                                                                         │
│              ┌────────────────────────────────────┐                   │
│              │      MIDDLEWARE LAYER              │                   │
│              ├─ auth:sanctum (API Auth)           │                   │
│              ├─ auth:admin (Admin Only)           │                   │
│              ├─ throttle (Rate Limiting)          │                   │
│              ├─ verified (Email Verification)     │                   │
│              └─ CORS (Cross-Origin Requests)      │                   │
│              └────────────────────────────────────┘                   │
│                                                                         │
│              ┌────────────────────────────────────┐                   │
│              │        MODELS / ENTITIES            │                   │
│              ├─ User, Admin, Customer             │                   │
│              ├─ Product, Brand, Category          │                   │
│              ├─ Order, OrderItem, Payment         │                   │
│              ├─ ReturnRequest, ReturnRequestItem  │                   │
│              ├─ Voucher, UserVoucher, VoucherUsage
│              ├─ Shipping, Notification            │                   │
│              └─ Audit, Log records                │                   │
│              └────────────────────────────────────┘                   │
│                                                                         │
│              ┌────────────────────────────────────┐                   │
│              │     BUSINESS LOGIC SERVICES         │                   │
│              ├─ AuthService                       │                   │
│              ├─ OrderService                      │                   │
│              ├─ PaymentService                    │                   │
│              ├─ RefundService                     │                   │
│              ├─ VoucherDistributionService        │                   │
│              ├─ ReturnRequestService              │                   │
│              ├─ NotificationService               │                   │
│              └─ AnalyticsService                  │                   │
│              └────────────────────────────────────┘                   │
│                                                                         │
└────────────┬──────────────────────────────────────┬────────────────────┘
             │                                      │
             ↓                                      ↓

┌────────────────────────────────────┐  ┌──────────────────────────────┐
│   EXTERNAL SERVICE INTEGRATIONS    │  │   DATA & CACHING LAYER       │
├────────────────────────────────────┤  ├──────────────────────────────┤
│                                    │  │                              │
│ PAYMENT GATEWAY                    │  │  POSTGRESQL / MYSQL 8.0      │
│ ├─ Stripe API                      │  │  ├─ Primary Database         │
│ ├─ PayPal API                      │  │  ├─ ACID Transactions        │
│ ├─ GCash API                       │  │  ├─ Indexes & Constraints    │
│ └─ Handle Payment Webhooks         │  │  └─ Master-Replica Setup     │
│                                    │  │                              │
│ EMAIL SERVICE                      │  │  REDIS CACHE LAYER           │
│ ├─ SendGrid SMTP                   │  │  ├─ Session Storage          │
│ ├─ Mailgun API                     │  │  ├─ Query Cache              │
│ ├─ AWS SES                         │  │  ├─ Rate Limiting            │
│ └─ Send Notifications              │  │  ├─ Pub/Sub (Queues)         │
│                                    │  │  └─ Real-time Updates        │
│ FILE STORAGE                       │  │                              │
│ ├─ AWS S3 Buckets                  │  │  SQLITE (Development)        │
│ ├─ Product Images                  │  │  ├─ Lightweight Dev DB       │
│ ├─ Documents/Invoices              │  │  ├─ Easy Setup               │
│ └─ User Uploads                    │  │  └─ Testing Support          │
│                                    │  │                              │
└────────────────────────────────────┘  └──────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────┐
│                     BACKGROUND JOBS / QUEUE LAYER                       │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│              ┌────────────────────────────────────┐                   │
│              │   REDIS Queue / Job Processor      │                   │
│              └───────────────┬────────────────────┘                   │
│                              │                                        │
│        ┌─────────────────────┼─────────────────────┐                 │
│        ↓                     ↓                     ↓                  │
│                                                                         │
│  ┌──────────────┐    ┌──────────────┐    ┌──────────────┐           │
│  │  Email Jobs  │    │   PDF/Report │    │   Analytics  │           │
│  │ • Confirm    │    │   Generation │    │    Jobs      │           │
│  │ • Notify     │    │ • Invoices   │    │ • Aggreg.   │           │
│  │ • Reset Pwd  │    │ • Receipts   │    │ • Reports    │           │
│  └──────────────┘    └──────────────┘    └──────────────┘           │
│                                                                         │
│  ┌──────────────┐    ┌──────────────┐    ┌──────────────┐           │
│  │ Inventory    │    │  Webhook     │    │  Scheduled   │           │
│  │  Jobs        │    │  Jobs        │    │  Jobs        │           │
│  │ • Update Qty │    │ • Payment    │    │ • Cleanup    │           │
│  │ • Reorder    │    │ • Shipping   │    │ • Reports    │           │
│  └──────────────┘    └──────────────┘    └──────────────┘           │
│                                                                         │
└─────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────┐
│                    MONITORING & LOGGING LAYER                           │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐                │
│  │ Application  │  │   Database   │  │   System     │                │
│  │  Logs        │  │    Logs      │  │   Logs       │                │
│  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘                │
│         │                 │                 │                         │
│         └─────────────────┼─────────────────┘                         │
│                           ↓                                           │
│                  ┌─────────────────┐                                  │
│                  │  ELK Stack or   │                                  │
│                  │  Datadog        │                                  │
│                  │  New Relic      │                                  │
│                  │  (Centralized   │                                  │
│                  │   Logging &     │                                  │
│                  │   Monitoring)   │                                  │
│                  └─────────────────┘                                  │
│                                                                         │
│  ┌──────────────────────────────────────────────────────┐             │
│  │  ERROR TRACKING & ALERTS                             │             │
│  │  ├─ Sentry (Error tracking)                          │             │
│  │  ├─ Alert Rules (CPU, Memory, Disk, Errors)          │             │
│  │  ├─ Health Checks (API endpoints monitoring)          │             │
│  │  └─ Dashboards (Real-time metrics)                    │             │
│  └──────────────────────────────────────────────────────┘             │
│                                                                         │
└─────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────┐
│                       CI/CD & DEPLOYMENT LAYER                          │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  GITHUB REPOSITORY                                                     │
│  ├─ main (Production)                                                 │
│  ├─ develop (Staging)                                                 │
│  └─ feature/* (Development)                                           │
│         │                                                              │
│         └──→  GITHUB ACTIONS CI/CD PIPELINE                          │
│              ├─ Trigger on git push/PR                               │
│              ├─ Run Tests (PHPUnit)                                  │
│              ├─ Code Quality (Pint, ESLint, SonarQube)               │
│              ├─ Build Artifacts                                      │
│              ├─ Deploy to Staging/Production                         │
│              └─ Post-deployment Health Checks                        │
│                                                                         │
│  DEPLOYMENT TARGETS                                                    │
│  ├─ Development (Docker Sail)                                         │
│  ├─ Staging (Testing environment)                                     │
│  └─ Production (Live environment)                                     │
│                                                                         │
└─────────────────────────────────────────────────────────────────────────┘

DATA FLOW SUMMARY:
═════════════════
1. Client → Frontend renders Nuxt.js pages
2. User interaction → API request via Sanctum token
3. Load Balancer → Routes to available app server
4. Middleware → Validates authentication & permissions
5. Controller → Handles business logic
6. Service → Executes complex operations
7. Model/Database → Persists/retrieves data
8. Cache Layer → Optimizes query performance
9. Queue Jobs → Async background processing
10. External APIs → Payment, email, storage
11. Logging → Centralized monitoring & analytics
12. Response → JSON back to frontend
13. Frontend → Updates UI with response data

SCALABILITY FEATURES:
════════════════════
• Horizontal scaling (multiple app servers)
• Load balancing (request distribution)
• Database replication (read scaling)
• Caching layer (performance optimization)
• Queue system (async task processing)
• CDN ready (static asset delivery)
• Stateless architecture (no sticky sessions)
```

---

## 3. Additional Resources

This project documentation provides a complete blueprint for the TechStore e-commerce platform. Refer to the following files for more specific implementation details:

- **Voucher System**: See `VOUCHER_FEATURE_OVERVIEW.md`, `VOUCHER_QUICK_START.md`
- **Return Management**: See `RETURN_REQUEST_COMPLETE.md`, `RETURN_REQUEST_DOCUMENTATION_INDEX.md`
- **Order Dashboard**: See `IMPLEMENTATION_COMPLETE.md`, `DASHBOARD_QUICK_START.md`
- **API Documentation**: See `README_API.txt`, `WEBSITE_API_COMPLETE_OVERVIEW.md`
- **Completion Status**: See `COMPLETION_CHECKLIST.md`, `FINAL_STATUS_REPORT.md`

---

**Last Updated:** December 22, 2025  
**Version:** 1.0  
**Status:** Complete & Production Ready
