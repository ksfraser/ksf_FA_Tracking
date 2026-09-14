# Architecture - ksf_FA_Tracking

## Document Information
| Field | Value |
|-------|-------|
| **Module** | ksf_FA_Tracking |
| **Version** | 1.0.0 |
| **Author** | KSFII Development Team |
| **Date** | 2026-05-12 |

---

## 1. Architecture Overview

### 1.1 Module Classification

**ksf_FA_Tracking** is classified as a **FrontAccounting Thin Adapter** module. It provides:
- FrontAccounting-specific database adapters
- FA hooks integration (hooks.php)
- Admin UI pages in FA's application structure
- Database schema using FA's table prefix conventions

### 1.2 Architecture Pattern

The module follows the **Business Logic + Platform Adapter** pattern:

```
┌─────────────────────────────────────────────────────────────────┐
│                     Business Logic Layer                         │
│                     (ksf_Tracking_Core)                        │
│  ┌─────────────────────────────────────────────────────────────┐│
│  │ VisitorService │ EventService │ AnalyticsService          ││
│  └─────────────────────────────────────────────────────────────┘│
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                    Platform Adapter Layer                        │
│                        ksf_FA_Tracking                          │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐  │
│  │ tracking_       │  │ events_         │  │ stats_           │  │
│  │     db.inc      │  │    db.inc       │  │    db.inc        │  │
│  └─────────────────┘  └─────────────────┘  └─────────────────┘  │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐  │
│  │   hooks.php     │  │   pages/       │  │  sql/install.sql│  │
│  └─────────────────┘  └─────────────────┘  └─────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                   FrontAccounting Platform                       │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────────────┐  │
│  │ debtors_master│  │ CRM App      │  │  Inquiry Pages       │  │
│  └──────────────┘  └──────────────┘  └──────────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
```

### 1.3 Design Principles

| Principle | Application |
|-----------|-------------|
| **SOLID** | Each DB function handles one responsibility |
| **DRY** | Shared visitor identification logic |
| **TDD** | Tests cover core functionality |
| **DI** | Services can be injected for testing |
| **SRP** | DB files separate by domain entity |

---

## 2. Component Architecture

### 2.1 Module Components

#### 2.1.1 Hooks (hooks.php)

The entry point for FrontAccounting integration:

```php
class hooks_fa_tracking extends hooks {
    var $module_name = 'fa_tracking';
    
    // Menu integration
    // Security areas definition
    // Database schema management
}
```

**Responsibilities:**
- Register module menu items under CRM application
- Define security areas (SA_TRACKINGVIEW)
- Manage database schema versioning
- Handle transaction callbacks

#### 2.1.2 Database Adapters (includes/)

| File | Responsibility | Public API |
|------|----------------|------------|
| `tracking_db.inc` | Visitor and event CRUD | `track_event()`, `get_tracking_stats()` |

#### 2.1.3 Pages (pages/)

Admin UI pages for:
- Visitor tracking list
- Event history
- Statistics display
- Data export

#### 2.1.4 Database Schema (sql/)

Tables using FrontAccounting's `TB_PREF` prefix:
- `fa_tracking_visitors` - Visitor records
- `fa_tracking_events` - Event records

### 2.2 Class Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                    FA Tracking Module                           │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌─────────────────────┐      ┌─────────────────────┐        │
│  │      Visitor        │      │      TrackingEvent   │        │
│  ├─────────────────────┤      ├─────────────────────┤        │
│  │ - id                │      │ - id                │        │
│  │ - visitor_id       │      │ - visitor_id       │        │
│  │ - ip_address       │      │ - event_type        │        │
│  │ - user_agent       │      │ - event_data        │        │
│  │ - first_visit      │      │ - page_url          │        │
│  │ - last_visit       │      │ - referrer          │        │
│  │ - visit_count      │      │ - event_time        │        │
│  │ - debtor_no        │      │ - contact_id        │        │
│  └─────────────────────┘      └─────────────────────┘        │
│           │                              │                     │
│           │ 1                            │ m                   │
│           └──────────────────────────────┘                     │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### 2.3 Database Schema

#### fa_tracking_visitors

```sql
CREATE TABLE fa_tracking_visitors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    visitor_id VARCHAR(100) NOT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT,
    first_visit DATETIME NOT NULL,
    last_visit DATETIME NOT NULL,
    visit_count INT(11) DEFAULT 1,
    debtor_no VARCHAR(20) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY idx_visitor_id (visitor_id),
    INDEX idx_debtor (debtor_no)
);
```

#### fa_tracking_events

```sql
CREATE TABLE fa_tracking_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    visitor_id VARCHAR(100) NOT NULL,
    event_type VARCHAR(50) NOT NULL,
    event_data TEXT,
    page_url VARCHAR(500) DEFAULT NULL,
    referrer VARCHAR(500) DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent VARCHAR(255) DEFAULT NULL,
    contact_id INT(11) DEFAULT NULL,
    event_time DATETIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_visitor (visitor_id),
    INDEX idx_event_type (event_type),
    INDEX idx_event_time (event_time),
    INDEX idx_contact (contact_id)
);
```

---

## 3. Data Flow Architecture

### 3.1 Visitor Creation Flow

```
┌─────────────┐    ┌──────────────┐    ┌─────────────────────┐
│ Website     │───▶│ create_      │───▶│ fa_tracking_visitors │
│ Visitor     │    │ visitor()    │    │ (Insert/Update)     │
└─────────────┘    └──────────────┘    └─────────────────────┘
                                                  │
                                                  ▼
                                         ┌─────────────────────┐
                                         │ If existing visitor  │
                                         │ Update last_visit    │
                                         │ Increment visit_count│
                                         └─────────────────────┘
```

### 3.2 Event Tracking Flow

```
┌─────────────┐    ┌──────────────┐    ┌─────────────────────┐
│ Website     │───▶│ track_event()│───▶│ fa_tracking_events  │
│ Page View   │    │ (DB Func)    │    │ (Insert)            │
└─────────────┘    └──────────────┘    └─────────────────────┘
                                                  │
                                                  ▼
                                         ┌─────────────────────┐
                                         │ Link to contact_id   │
                                         │ if user logged in    │
                                         └─────────────────────┘
```

### 3.3 Statistics Flow

```
┌─────────────┐    ┌──────────────┐    ┌─────────────────────┐
│ Admin       │───▶│ get_tracking_│───▶│ Query aggregated    │
│ Dashboard   │    │ stats()      │    │ data for display    │
└─────────────┘    └──────────────┘    └─────────────────────┘
```

---

## 4. Integration Architecture

### 4.1 FrontAccounting Integration Points

#### Security Areas
```php
define('SS_TRACKING', 139 << 8);
$security_areas['SA_TRACKINGVIEW'] = array(SS_TRACKING | 1, _("View Tracking"));
```

#### Menu Integration
```php
// Under CRM application
$app->add_lapp_function(0, _("Visitor Tracking"), ..., 'SA_TRACKINGVIEW', MENU_INQUIRY);
$app->add_lapp_function(1, _("Tracking Events"), ..., 'SA_TRACKINGVIEW', MENU_INQUIRY);
```

### 4.2 Website Integration

JavaScript tracking snippet pattern:

```javascript
// Track page view
fa_tracking.track('page_view', {
    url: window.location.href,
    referrer: document.referrer
});

// Track custom events
fa_tracking.track('form_submit', {
    form_id: 'contact-form',
    element_id: 'submit-btn'
});
```

### 4.3 CRM Integration

Visitors linked to customers via debtor_no:

```sql
SELECT v.*, d.name as customer_name
FROM fa_tracking_visitors v
LEFT JOIN debtors_master d ON v.debtor_no = d.debtor_no
WHERE v.debtor_no IS NOT NULL
```

---

## 5. Module Structure

```
ksf_FA_Tracking/
├── hooks.php                    # FA hooks class
├── includes/
│   ├── tracking_db.inc         # Visitor/Event CRUD
│   └── stats_db.inc             # Statistics queries
├── pages/
│   ├── visitors.php             # Visitor list
│   ├── events.php               # Event history
│   └── stats.php                # Statistics dashboard
├── sql/
│   ├── install.sql              # Schema installation
│   └── update.sql               # Version migrations
└── ProjectDcs/
    ├── Business Requirements.md
    ├── Architecture.md
    ├── Functional Requirements.md
    ├── Use Case.md
    ├── Test Plan.md
    └── UAT Plan.md
```

---

## 6. Error Handling

### 6.1 Database Errors

All DB operations use FA's error handling:

```php
$result = db_query($sql, "Could not track event");
if ($result === false) {
    // Log error, don't fail the page load
}
```

### 6.2 Visitor Not Found

Graceful handling when visitor_id not found:

```php
function track_event(string $visitor_id, ...): int
{
    // Create visitor if not exists
    // Continue with event tracking
}
```

### 6.3 Invalid Event Data

Input validation before database operations:

```php
function track_event(string $visitor_id, string $event_type, ...): int
{
    // Validate visitor_id format
    // Validate event_type is known type
    // Sanitize URL and referrer
}
```

---

*Document Version: 1.0.0*  
*Last Updated: 2026-05-12*