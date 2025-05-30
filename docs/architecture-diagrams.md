# SmartCart Architecture Diagrams

This document contains Mermaid diagrams representing the SmartCart application architecture.

## System Overview Diagram

```mermaid
graph TB
    subgraph "🎨 Client Layer"
        UI["👤 User Interface<br/>🛒 Shopping Experience"]
        Admin["👨‍💼 Admin Interface<br/>📊 Management Dashboard"]
    end
    
    subgraph "🏗️ Infrastructure Layer"
        Docker["🐳 Docker Containers<br/>📦 Containerization"]
        Nginx["🌐 Nginx Web Server<br/>⚡ Load Balancing"]
        PHPFPM["🐘 PHP-FPM<br/>🚀 Process Manager"]
    end
    
    subgraph "🎭 Frontend Layer"
        Blade["📄 Laravel Blade<br/>🎨 Template Engine"]
        Livewire["⚡ Livewire Components<br/>🔄 Dynamic UI"]
        Bootstrap["🎨 Bootstrap 5<br/>📱 Responsive Design"]
        Alpine["🏔️ Alpine.js<br/>✨ Minimal Framework"]
        Sneat["💎 Sneat Template<br/>🎯 Admin Interface"]
    end
    
    subgraph "⚙️ Backend Layer"
        Router["🚦 Laravel Router<br/>📍 Request Routing"]
        Middleware["🛡️ Middleware<br/>🔒 Security & Auth"]
        Controllers["🎮 Controllers<br/>📋 Request Handlers"]
        Services["🔧 Service Layer<br/>💼 Business Logic"]
        Repositories["📚 Repository Layer<br/>💾 Data Access"]
    end
    
    subgraph "🗄️ Database Layer"
        MySQL[("🗃️ MySQL Database<br/>📊 Data Storage")]
        Eloquent["🔗 Eloquent ORM<br/>🎯 Object Mapping"]
    end
    
    %% Client to Infrastructure connections
    UI -.->|HTTPS| Nginx
    Admin -.->|HTTPS| Nginx
    
    %% Infrastructure connections
    Nginx -->|FastCGI| PHPFPM
    PHPFPM -->|Process| Router
    
    %% Backend flow connections
    Router -->|Route| Middleware
    Middleware -->|Authorize| Controllers
    Middleware -->|Authorize| Livewire
    Controllers -->|Delegate| Services
    Livewire -->|Interact| Services
    Services -->|Query| Repositories
    Repositories -->|Access| Eloquent
    Eloquent -->|SQL| MySQL
    
    %% Frontend styling connections
    Blade -.->|Uses| Bootstrap
    Blade -.->|Enhanced by| Alpine
    Admin -.->|Styled with| Sneat
    Livewire -.->|Renders| Blade
    
    %% Styling
    classDef clientLayer fill:#e1f5fe,stroke:#01579b,stroke-width:2px,color:#000
    classDef infraLayer fill:#f3e5f5,stroke:#4a148c,stroke-width:2px,color:#000
    classDef frontendLayer fill:#e8f5e8,stroke:#1b5e20,stroke-width:2px,color:#000
    classDef backendLayer fill:#fff3e0,stroke:#e65100,stroke-width:2px,color:#000
    classDef dbLayer fill:#fce4ec,stroke:#880e4f,stroke-width:2px,color:#000
    
    class UI,Admin clientLayer
    class Docker,Nginx,PHPFPM infraLayer
    class Blade,Livewire,Bootstrap,Alpine,Sneat frontendLayer
    class Router,Middleware,Controllers,Services,Repositories backendLayer
    class MySQL,Eloquent dbLayer
```

## Request Flow Diagram

```mermaid
sequenceDiagram
    participant Client
    participant Nginx
    participant Router
    participant Middleware
    participant Controller
    participant Service
    participant Repository
    participant Model
    participant Database
    participant View
    
    Client->>Nginx: HTTP Request
    Nginx->>Router: Forward Request
    Router->>Middleware: Route to Handler
    Middleware->>Controller: Apply Middleware
    Controller->>Service: Business Logic
    Service->>Repository: Data Access
    Repository->>Model: ORM Operations
    Model->>Database: SQL Queries
    Database-->>Model: Query Results
    Model-->>Repository: Eloquent Collection
    Repository-->>Service: Processed Data
    Service-->>Controller: Business Results
    Controller->>View: Pass Data
    View-->>Client: Rendered HTML
```

## Database Schema Diagram

```mermaid
erDiagram
    USERS {
        int id PK
        string name
        string email UK
        timestamp email_verified_at
        string password
        string role
        timestamps created_at
        timestamps updated_at
    }
    
    CATEGORIES {
        int id PK
        string name
        string description
        string slug UK
        timestamps created_at
        timestamps updated_at
    }
    
    PRODUCTS {
        int id PK
        string name
        text description
        decimal price
        int stock_quantity
        string sku UK
        string image_url
        int category_id FK
        timestamps created_at
        timestamps updated_at
    }
    
    CARTS {
        int id PK
        int user_id FK
        timestamps created_at
        timestamps updated_at
    }
    
    CART_ITEMS {
        int id PK
        int cart_id FK
        int product_id FK
        int quantity
        decimal price
        timestamps created_at
        timestamps updated_at
    }
    
    ORDERS {
        int id PK
        int user_id FK
        decimal total_amount
        string status
        text shipping_address
        timestamps created_at
        timestamps updated_at
    }
    
    ORDER_ITEMS {
        int id PK
        int order_id FK
        int product_id FK
        int quantity
        decimal price
        timestamps created_at
        timestamps updated_at
    }
    
    USERS ||--o{ CARTS : "has"
    USERS ||--o{ ORDERS : "places"
    CATEGORIES ||--o{ PRODUCTS : "contains"
    CARTS ||--o{ CART_ITEMS : "contains"
    PRODUCTS ||--o{ CART_ITEMS : "added to"
    ORDERS ||--o{ ORDER_ITEMS : "contains"
    PRODUCTS ||--o{ ORDER_ITEMS : "included in"
```

## Livewire Component Architecture

```mermaid
graph TD
    subgraph "Frontend Components"
        ProductList[ProductList Component]
        ProductSearch[ProductSearch Component]
        ShoppingCart[ShoppingCart Component]
        CheckoutForm[CheckoutForm Component]
        AdminProductTable[AdminProductTable Component]
        AdminOrderTable[AdminOrderTable Component]
    end
    
    subgraph "Backend Services"
        ProductService[Product Service]
        CartService[Cart Service]
        OrderService[Order Service]
        UserService[User Service]
    end
    
    subgraph "Models"
        ProductModel[Product Model]
        CartModel[Cart Model]
        OrderModel[Order Model]
        UserModel[User Model]
    end
    
    ProductList --> ProductService
    ProductSearch --> ProductService
    ShoppingCart --> CartService
    CheckoutForm --> OrderService
    AdminProductTable --> ProductService
    AdminOrderTable --> OrderService
    
    ProductService --> ProductModel
    CartService --> CartModel
    CartService --> ProductModel
    OrderService --> OrderModel
    OrderService --> ProductModel
    UserService --> UserModel
```

## Authentication Flow

```mermaid
sequenceDiagram
    participant User
    participant Frontend
    participant AuthController
    participant Sanctum
    participant Database
    participant Session
    
    User->>Frontend: Submit Credentials
    Frontend->>AuthController: Login Request
    AuthController->>Sanctum: Validate Credentials
    Sanctum->>Database: Check User Credentials
    Database-->>Sanctum: User Data
    Sanctum-->>AuthController: Authentication Result
    
    alt Authentication Successful
        AuthController->>Session: Create User Session
        AuthController->>Sanctum: Generate Token
        Sanctum-->>AuthController: Auth Token
        AuthController-->>Frontend: Success + Token
        Frontend-->>User: Redirect to Dashboard
    else Authentication Failed
        AuthController-->>Frontend: Error Response
        Frontend-->>User: Show Error Message
    end
```

## Service Layer Architecture

```mermaid
graph TB
    subgraph "Controllers & Components"
        PC[Product Controller]
        CC[Cart Controller]
        OC[Order Controller]
        UC[User Controller]
        LC[Livewire Components]
    end
    
    subgraph "Service Layer"
        PS[Product Service]
        CS[Cart Service]
        OS[Order Service]
        US[User Service]
        RS[Recommendation Service]
        PayS[Payment Service]
    end
    
    subgraph "Repository Layer"
        PR[Product Repository]
        CR[Cart Repository]
        OR[Order Repository]
        UR[User Repository]
    end
    
    subgraph "Models"
        PM[Product Model]
        CM[Cart Model]
        OM[Order Model]
        UM[User Model]
    end
    
    PC --> PS
    CC --> CS
    OC --> OS
    UC --> US
    LC --> PS
    LC --> CS
    LC --> OS
    
    PS --> PR
    CS --> CR
    OS --> OR
    US --> UR
    
    CS --> PS
    OS --> PS
    OS --> CS
    
    PR --> PM
    CR --> CM
    OR --> OM
    UR --> UM
```

## Deployment Architecture

```mermaid
graph TB
    subgraph "Docker Environment"
        subgraph "Web Container"
            Nginx[Nginx Web Server]
            PHP[PHP-FPM]
        end
        
        subgraph "Application Container"
            Laravel[Laravel Application]
            Livewire[Livewire Components]
            Services[Service Layer]
        end
        
        subgraph "Database Container"
            MySQL[(MySQL Database)]
        end
        
        subgraph "Cache Container"
            Redis[(Redis Cache)]
        end
    end
    
    Client[Client Browser] --> Nginx
    Nginx --> PHP
    PHP --> Laravel
    Laravel --> Services
    Services --> MySQL
    Laravel --> Redis
    
    subgraph "External Services"
        CDN[Content Delivery Network]
        Payment[Payment Gateway]
    end
    
    Client --> CDN
    Services --> Payment
``` 