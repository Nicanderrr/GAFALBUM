# GAF Album System Logic Flowchart

This document summarizes the main logic flows in the Laravel-based GAF Album system.

## 1. Overall System Flow

```mermaid
flowchart TD
    A([Start]) --> B[User opens system]
    B --> C{Authenticated?}

    C -- No --> D{Login type}
    D -- Regular user --> E[Enter service number]
    D -- Admin --> F[Enter service number and password]

    E --> G{Valid regular user?}
    F --> H{Valid admin?}

    G -- No --> I[Show login error / rate limit if needed]
    H -- No --> I
    I --> D

    G -- Yes --> J[Open user dashboard]
    H -- Yes --> K[Open admin dashboard]

    C -- Yes --> L{Is admin route requested?}
    L -- Yes --> M{User is admin?}
    M -- Yes --> K
    M -- No --> N[Logout and redirect to login]
    L -- No --> J

    J --> O{User action}
    O -- Browse gallery --> P[Search / filter / sort published events]
    O -- View event --> Q[Open event details or experience view]
    O -- Manage cart --> R[View, add, or remove cart items]
    O -- Checkout --> S[Paystack payment flow]
    O -- View purchases --> T[Show successful transactions]
    O -- Download media --> U[Verify ownership and transaction success]

    K --> V{Admin action}
    V -- Manage categories --> W[Create / update / delete categories]
    V -- Manage events/media --> X[Create / update / import / delete events]
    V -- Manage users --> Y[Create / import / update / delete users]
    V -- Manage admins --> Z[Create / update / delete admin accounts]
    V -- Review payments --> AA[Filter and view transactions]
    V -- Site settings --> AB[Update hero images and protection settings]
    V -- AI assistant --> AC[Chat or analyze system context]

    U --> AD{Allowed?}
    AD -- Yes --> AE[Download file from public storage]
    AD -- No --> AF[Return 403 or 404]

    S --> AG[Payment completed or failed]
    AG --> O
```

## 2. User Gallery, Cart, Payment, and Download Flow

```mermaid
flowchart TD
    A([Authenticated user]) --> B[Open gallery]
    B --> C[Load published images with category, cover media, media count]
    C --> D{Search, category, or sort selected?}
    D -- Yes --> E[Apply filters and ordering]
    D -- No --> F[Show featured randomized listing]
    E --> G[Display gallery]
    F --> G

    G --> H[Open event]
    H --> I[Load published event and media files]
    I --> J{User already purchased media?}
    J -- Yes --> K[Show download access]
    J -- No --> L[Show add-to-cart or buy-now option]

    L --> M{Selected action}
    M -- Add to cart --> N[Create cart item if not already present]
    N --> O[Return gallery/event/cart response]

    M -- Buy now --> P[Check existing successful purchase]
    P --> Q{Already purchased?}
    Q -- Yes --> R[Redirect to download]
    Q -- No --> S[Create pending transaction and transaction item]
    S --> T[Initialize Paystack payment]

    M -- Checkout cart --> U[Load user's cart items]
    U --> V{Cart empty?}
    V -- Yes --> W[Redirect to cart with error]
    V -- No --> X[Create pending transaction with all cart items]
    X --> T

    T --> Y{Authorization URL returned?}
    Y -- No --> Z[Mark transaction failed and show Paystack error]
    Y -- Yes --> AA[Redirect user to Paystack]

    AA --> AB[Paystack redirects to callback with reference]
    AB --> AC{Reference exists?}
    AC -- No --> AD[Redirect to cart with missing reference error]
    AC -- Yes --> AE[Find user's pending transaction]
    AE --> AF{Transaction already successful?}
    AF -- Yes --> AG[Redirect to purchases]
    AF -- No --> AH[Verify payment with Paystack]

    AH --> AI{Status success and paid amount >= expected amount?}
    AI -- No --> AJ[Update transaction as failed or returned status]
    AJ --> AK[Redirect to cart with payment error]

    AI -- Yes --> AL[Mark transaction successful]
    AL --> AM[Delete matching cart items]
    AM --> AN{Single item transaction?}
    AN -- Yes --> AO[Redirect back with download unlocked]
    AN -- No --> AP[Redirect to purchases]

    K --> AQ[Download request]
    R --> AQ
    AO --> AQ
    AP --> AQ
    AQ --> AR{Owner and successful transaction?}
    AR -- No --> AS[Abort 403]
    AR -- Yes --> AT{File exists in public storage?}
    AT -- No --> AU[Abort 404]
    AT -- Yes --> AV[Download media file]
```

## 3. Admin Event and Media Management Flow

```mermaid
flowchart TD
    A([Authenticated admin]) --> B[Open admin dashboard]
    B --> C{Admin selects module}

    C -- Events / images --> D[Open event listing]
    D --> E{Filter by search, status, or category?}
    E -- Yes --> F[Apply filters]
    E -- No --> G[Load latest events]
    F --> H[Show paginated event list and summary counts]
    G --> H

    H --> I{Event action}
    I -- Create --> J[Fill title, description, category, price, status, media files]
    J --> K{Validation passes?}
    K -- No --> L[Return errors]
    K -- Yes --> M{First file is image?}
    M -- No --> L
    M -- Yes --> N[Store uploaded files on public disk]
    N --> O[Create image/event record]
    O --> P[Create image_media records]
    P --> Q[Set first image media as cover and thumbnail]
    Q --> H

    I -- Edit/update --> R[Load event with media and cover]
    R --> S[Validate fields, optional uploads, cover, and removed media]
    S --> T{Validation passes?}
    T -- No --> L
    T -- Yes --> U[Update event fields and publication date]
    U --> V[Delete selected media files and records]
    V --> W[Append new uploaded media]
    W --> X{At least one media file remains?}
    X -- No --> L
    X -- Yes --> Y{At least one image remains for thumbnail?}
    Y -- No --> L
    Y -- Yes --> Z[Resolve cover image]
    Z --> AA{Cover is image?}
    AA -- No --> L
    AA -- Yes --> AB[Update cover, file path, and thumbnail path]
    AB --> H

    I -- Delete --> AC[Load event and media]
    AC --> AD[Delete media files from storage]
    AD --> AE[Delete event record]
    AE --> H

    I -- Import spreadsheet --> AF[Upload xlsx, xls, csv, or txt]
    AF --> AG[Read active sheet rows]
    AG --> AH{Has data rows?}
    AH -- No --> AI[Return import report with error]
    AH -- Yes --> AJ[Normalize headers and map rows]
    AJ --> AK{Row valid?}
    AK -- No --> AL[Skip row and collect errors]
    AK -- Yes --> AM[Create or update category by slug]
    AM --> AN[Create event record]
    AN --> AO[Create media records from public storage paths]
    AO --> AP[Set cover media]
    AL --> AQ[Continue next row]
    AP --> AQ
    AQ --> AR{More rows?}
    AR -- Yes --> AJ
    AR -- No --> AS[Return import report]
    AI --> H
    AS --> H
```

## 4. Admin User Management Flow

```mermaid
flowchart TD
    A([Authenticated admin]) --> B[Open users module]
    B --> C[Load non-admin users and missing password count]
    C --> D{Admin action}

    D -- Create user --> E[Enter name, optional email, service number]
    E --> F{Valid and unique?}
    F -- No --> G[Return validation errors]
    F -- Yes --> H[Generate random password]
    H --> I[Create user]
    I --> J{Email provided?}
    J -- Yes --> K[Send created-user email with password]
    J -- No --> L[Skip email]
    K --> C
    L --> C

    D -- Edit user --> M[Update name, optional email, service number]
    M --> N{Valid and unique?}
    N -- No --> G
    N -- Yes --> O[Save user changes]
    O --> C

    D -- Delete user --> P[Delete user record]
    P --> C

    D -- Import users --> Q[Upload spreadsheet]
    Q --> R[Read rows and normalize headers]
    R --> S{Row valid?}
    S -- No --> T[Skip row and collect errors]
    S -- Yes --> U[Generate password and create user]
    U --> V{Email provided?}
    V -- Yes --> W[Send created-user email]
    V -- No --> X[Skip email]
    T --> Y{More rows?}
    W --> Y
    X --> Y
    Y -- Yes --> R
    Y -- No --> Z[Return import report]
    Z --> C

    D -- Assign service-number passwords --> AA[Find non-admin users with missing password]
    AA --> AB[Set password to service number]
    AB --> C
```

## 5. Main Data Relationship Flow

```mermaid
flowchart LR
    User[users] -->|has many| CartItem[cart_items]
    User -->|has many| Transaction[transactions]
    User -->|admin_id uploads| Image[images/events]

    Category[categories] -->|has many| Image
    Image -->|has many| ImageMedia[image_media]
    Image -->|cover_media_id| ImageMedia

    CartItem -->|belongs to| Image
    CartItem -->|selected file| ImageMedia

    Transaction -->|has many| TransactionItem[transaction_items]
    Transaction -->|belongs to| Image
    TransactionItem -->|belongs to| Image
    TransactionItem -->|download file| ImageMedia

    SiteHero[site_heroes] -->|controls| PublicPages[public/user-facing pages]
    SiteSetting[site_settings] -->|controls| SiteProtection[site protection behavior]
```

## 6. High-Level Modules

- Authentication: regular users log in with service number; admins log in with service number and password.
- User gallery: authenticated users browse only published events.
- Cart: users add individual media items and checkout one or more items.
- Payment: Paystack is initialized from a pending local transaction, then verified on callback.
- Purchases: only successful transactions unlock media downloads.
- Admin dashboard: admins manage categories, events/media, users, admins, payments, hero images, site protection, and AI assistance.
- Storage: uploaded event files are stored on Laravel's public disk and downloaded only after ownership and payment checks.
