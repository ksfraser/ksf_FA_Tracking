# Use Case - ksf_FA_Tracking

## Document Information
| Field | Value |
|-------|-------|
| **Module** | ksf_FA_Tracking |
| **Version** | 1.0.0 |
| **Author** | KSFII Development Team |
| **Date** | 2026-05-12 |

---

## 1. Use Case Overview

### 1.1 Actor Definitions

| Actor | Description | Role |
|-------|-------------|------|
| **Website Visitor** | Anonymous website user | Tracked automatically |
| **Logged-in User** | FA authenticated user | Events linked to contact |
| **Marketing** | Marketing team member | Reviews visitor/analytics data |
| **Sales** | Sales representative | Reviews customer engagement |
| **Administrator** | System administrator | Configures tracking, views data |

### 1.2 Use Case Summary

| ID | Use Case | Primary Actor | Priority |
|----|----------|---------------|----------|
| UC-001 | Track Page View | Website Visitor | High |
| UC-002 | Track Custom Event | Website Visitor | High |
| UC-003 | Update Visitor Record | System | High |
| UC-004 | Link Visitor to Customer | Sales/Admin | High |
| UC-005 | View Visitor Details | Marketing/Sales | High |
| UC-006 | View Event History | Marketing/Sales | High |
| UC-007 | Filter Visitors | Marketing | Medium |
| UC-008 | Filter Events | Marketing | Medium |
| UC-009 | View Statistics | Marketing | Medium |
| UC-010 | Export Tracking Data | Administrator | Low |
| UC-011 | Customer Activity Summary | Sales | Medium |

---

## 2. Use Case Specifications

### UC-001: Track Page View

**Primary Actor:** Website Visitor  
**Priority:** High  
**Description:** System tracks when a visitor views a page.

#### Preconditions
- Tracking script installed on website
- Visitor accessing tracked page

#### Basic Flow
1. Visitor loads page
2. Tracking script generates/retrieves visitor_id (cookie or generate)
3. Script sends event to tracking endpoint:
   - visitor_id
   - event_type: 'page_view'
   - page_url
   - referrer
   - ip_address (from server)
   - user_agent
4. System creates visitor record if not exists
5. System records event
6. Response sent (non-blocking)

#### Postconditions
- Visitor record created/updated
- Page view event recorded

#### Acceptance Criteria
- Visitor tracked on first visit
- Subsequent visits update visitor record
- Page view event recorded with URL

---

### UC-002: Track Custom Event

**Primary Actor:** Website Visitor  
**Priority:** High  
**Description:** System tracks custom interactions (clicks, form submits, etc.).

#### Preconditions
- Tracking script installed
- Custom event triggered on website

#### Basic Flow
1. Visitor triggers custom event (click button, submit form)
2. Tracking script captures:
   - event_type (e.g., 'form_submit')
   - event_data (JSON payload)
   - context (page, element)
3. Script sends event to tracking endpoint
4. System records event linked to visitor

#### Postconditions
- Custom event recorded with payload

#### Acceptance Criteria
- Custom events tracked with data
- Linked to correct visitor

---

### UC-003: Update Visitor Record

**Primary Actor:** System  
**Priority:** High  
**Description:** System updates visitor record on subsequent visits.

#### Preconditions
- Visitor exists in system
- Visitor returns to website

#### Basic Flow
1. Visitor returns to website
2. Tracking script sends page view event
3. System finds existing visitor by visitor_id
4. System updates:
   - last_visit = current timestamp
   - visit_count += 1
5. Event recorded

#### Postconditions
- Visitor visit_count incremented
- last_visit updated

#### Acceptance Criteria
- Visit count accurate
- Last visit timestamp updated

---

### UC-004: Link Visitor to Customer

**Primary Actor:** Sales/Administrator  
**Priority:** High  
**Description:** Associate anonymous visitor with known customer.

#### Preconditions
- Visitor record exists
- Customer record exists in FA

#### Basic Flow
1. Sales identifies visitor (e.g., via email link click)
2. Admin/ Sales navigates to visitor detail
3. Admin searches/selects customer (debtor)
4. System updates visitor:
   - debtor_no = selected customer
5. System confirms link

#### Alternative Flows

**A1: Customer Not Found**
- At step 3, search returns no results
- Admin creates customer first
- Returns to step 3

#### Postconditions
- Visitor linked to customer
- Can query all activity for customer

#### Acceptance Criteria
- debtor_no stored in visitor record
- Link persists

---

### UC-005: View Visitor Details

**Primary Actor:** Marketing/Sales  
**Priority:** High  
**Description:** View complete information about a visitor.

#### Preconditions
- Visitor exists

#### Basic Flow
1. User navigates to Visitor Tracking page
2. User searches for visitor by ID or filters
3. User clicks on visitor
4. System displays visitor detail:
   - Visitor ID
   - First visit, last visit
   - Visit count
   - Linked customer (if any)
   - Event timeline

#### Postconditions
- Full visitor data displayed

#### Acceptance Criteria
- All visitor information shown
- Event timeline complete

---

### UC-006: View Event History

**Primary Actor:** Marketing/Sales  
**Priority:** High  
**Description:** View all events for a visitor.

#### Preconditions
- Visitor exists with events

#### Basic Flow
1. User on visitor detail page
2. User views "Event History" section
3. System displays events:
   - Event type
   - Page/URL
   - Timestamp
   - Event data (expandable)
4. User can filter by event type

#### Postconditions
- Event history displayed

#### Acceptance Criteria
- All events shown
- Filter works
- Event data expandable

---

### UC-007: Filter Visitors

**Primary Actor:** Marketing  
**Priority:** Medium  
**Description:** Filter visitor list by criteria.

#### Preconditions
- User on visitor list page

#### Basic Flow
1. User selects filter criteria:
   - Date range (first_visit)
   - Linked customer (debtor_no)
   - Visit count (min/max)
2. User clicks "Apply"
3. System displays filtered results
4. User can clear filters

#### Postconditions
- Results match filter criteria

#### Acceptance Criteria
- Filters work correctly
- Clear resets view

---

### UC-008: Filter Events

**Primary Actor:** Marketing  
**Priority:** Medium  
**Description:** Filter events by type and date.

#### Preconditions
- User on event history page

#### Basic Flow
1. User selects filter:
   - Event type
   - Date range
2. User clicks "Apply"
3. System displays filtered events

#### Postconditions
- Only matching events shown

#### Acceptance Criteria
- Filter accurate
- Combined filters work

---

### UC-009: View Statistics

**Primary Actor:** Marketing  
**Priority:** Medium  
**Description:** View aggregated tracking statistics.

#### Preconditions
- User has access to statistics page

#### Basic Flow
1. User navigates to Statistics page
2. User selects date range (default: last 30 days)
3. System displays:
   - Total visitors
   - Total events
   - Events by type
   - Top pages

#### Postconditions
- Statistics displayed for date range

#### Acceptance Criteria
- Metrics accurate
- Date range filter works

---

### UC-010: Export Tracking Data

**Primary Actor:** Administrator  
**Priority:** Low  
**Description:** Export tracking data for analysis.

#### Preconditions
- User has admin access

#### Basic Flow
1. User navigates to export page
2. User selects:
   - Data type (visitors/events)
   - Date range
   - Format (CSV/Excel)
3. User clicks "Export"
4. System generates file
5. File downloaded

#### Postconditions
- Data file generated and downloaded

#### Acceptance Criteria
- Export contains requested data
- Format correct

---

### UC-011: Customer Activity Summary

**Primary Actor:** Sales  
**Priority:** Medium  
**Description:** View website activity for a customer.

#### Preconditions
- Customer exists with linked visitors

#### Basic Flow
1. Sales navigates to customer in CRM
2. Sales views "Website Activity" section
3. System displays:
   - Visit count
   - Last visit
   - Events by type
   - Recent activity timeline

#### Postconditions
- Activity summary displayed

#### Acceptance Criteria
- All linked visitor activity shown
- Summary accurate

---

## 3. Use Case Relationships

### 3.1 Use Case Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                    Website/System                               │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌─────────────────────┐    ┌─────────────────────┐             │
│  │   Track Page View  │    │  Track Custom Event │             │
│  └──────────┬──────────┘    └──────────┬──────────┘             │
│             │                          │                        │
│             └──────────────────────────┘                        │
│                          │                                       │
│                          ▼                                       │
│                 ┌─────────────────────┐                         │
│                 │ Update Visitor      │                         │
│                 │ Record              │                         │
│                 └──────────┬──────────┘                         │
│                            │                                     │
└────────────────────────────┼────────────────────────────────────┘
                             │
                             ▼
                    ┌─────────────────────┐
                    │   FrontAccounting   │
                    ├─────────────────────┤
                    │                     │
    ┌───────────────┼───────────┬────────┼───────────────┐
    │               │           │        │               │
    ▼               ▼           ▼        ▼               ▼
┌───────────┐ ┌───────────┐ ┌───────────┐ ┌───────────┐ ┌───────────┐
│ View      │ │ Link      │ │ View      │ │ View      │ │ Export   │
│ Visitor   │ │ Visitor   │ │ Events   │ │ Stats     │ │ Data     │
│ Details   │ │ Customer  │ │          │ │           │ │           │
└───────────┘ └───────────┘ └───────────┘ └───────────┘ └───────────┘
```

---

*Document Version: 1.0.0*  
*Last Updated: 2026-05-12*