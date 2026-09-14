# Test Plan - ksf_FA_Tracking

## Document Information
| Field | Value |
|-------|-------|
| **Module** | ksf_FA_Tracking |
| **Version** | 1.0.0 |
| **Author** | KSFII Development Team |
| **Date** | 2026-05-12 |

---

## 1. Introduction

### 1.1 Purpose

This Test Plan defines the testing approach, scenarios, and criteria for the ksf_FA_Tracking module.

### 1.2 Scope

- Visitor tracking testing
- Event recording testing
- CRM integration testing
- Statistics calculation testing
- Admin UI testing

### 1.3 Test Strategy

| Type | Approach |
|------|----------|
| **Unit Tests** | Test DB functions and business logic |
| **Integration Tests** | Test module with FA |
| **UI Tests** | Manual testing of admin pages |

---

## 2. Test Scenarios

### 2.1 Visitor Management

#### TC-VIS-001: Track New Visitor
**Test ID:** TC-VIS-001  
**Priority:** High  
**Requirement:** FR-VIS-001

**Test Steps:**
1. Invoke `track_visitor()` with:
   - visitor_id: 'v-abc123'
   - ip_address: '192.168.1.1'
   - user_agent: 'Mozilla/5.0...'
2. Verify visitor record created
3. Verify first_visit = current timestamp
4. Verify visit_count = 1

**Expected Result:** Visitor created with correct data

**Pass Criteria:** Record exists with all fields correct

---

#### TC-VIS-002: Update Existing Visitor
**Test ID:** TC-VIS-002  
**Priority:** High  
**Requirement:** FR-VIS-002

**Test Steps:**
1. Create visitor (visit_count = 1)
2. Invoke track_visitor with same visitor_id
3. Verify visit_count = 2
4. Verify last_visit updated

**Expected Result:** Visit count incremented, last_visit updated

**Pass Criteria:** Count incremented, timestamp updated

---

#### TC-VIS-003: Link Visitor to Customer
**Test ID:** TC-VIS-003  
**Priority:** High  
**Requirement:** FR-VIS-003

**Test Steps:**
1. Create visitor
2. Create customer (debtor)
3. Invoke link_visitor_to_customer('v-abc123', debtor_no)
4. Verify visitor.debtor_no = customer.debtor_no

**Expected Result:** Visitor linked to customer

**Pass Criteria:** debtor_no stored correctly

---

#### TC-VIS-004: Get Visitor Details
**Test ID:** TC-VIS-004  
**Priority:** High  
**Requirement:** FR-VIS-004

**Test Steps:**
1. Create visitor with known data
2. Create linked customer
3. Record 5 events
4. Invoke get_visitor('v-abc123')
5. Verify includes customer_name and event_count

**Expected Result:** Full visitor data with related info

**Pass Criteria:** All related data included

---

#### TC-VIS-005: List Visitors with Filter
**Test ID:** TC-VIS-005  
**Priority:** Medium  
**Requirement:** FR-VIS-005

**Test Steps:**
1. Create 5 visitors: 2 linked to customer A, 3 unlinked
2. Invoke list_visitors with filter debtor_no = A
3. Verify only 2 returned

**Expected Result:** Filter returns correct visitors

**Pass Criteria:** Correct count and data

---

### 2.2 Event Tracking

#### TC-EVT-001: Record Page View Event
**Test ID:** TC-EVT-001  
**Priority:** High  
**Requirement:** FR-EVT-001

**Test Steps:**
1. Create visitor
2. Invoke track_event() with:
   - visitor_id: 'v-abc123'
   - event_type: 'page_view'
   - page_url: 'https://example.com/page1'
   - referrer: 'https://google.com'
3. Verify event created
4. Verify event_time = current timestamp

**Expected Result:** Page view event recorded

**Pass Criteria:** Event with all context created

---

#### TC-EVT-002: Record Custom Event with Data
**Test ID:** TC-EVT-002  
**Priority:** High  
**Requirement:** FR-EVT-001

**Test Steps:**
1. Create visitor
2. Invoke track_event() with:
   - event_type: 'form_submit'
   - event_data: '{"form_id":"contact","fields":5}'
3. Verify event_data stored correctly

**Expected Result:** Custom event with JSON data stored

**Pass Criteria:** event_data preserved correctly

---

#### TC-EVT-003: Get Events by Visitor
**Test ID:** TC-EVT-003  
**Priority:** High  
**Requirement:** FR-EVT-002

**Test Steps:**
1. Create visitor
2. Record 10 events (various types)
3. Invoke get_events_for_visitor('v-abc123')
4. Verify 10 events returned
5. Verify ordered by event_time DESC

**Expected Result:** All visitor events returned

**Pass Criteria:** All events, correct order

---

#### TC-EVT-004: Filter Events by Type
**Test ID:** TC-EVT-004  
**Priority:** Medium  
**Requirement:** FR-EVT-003

**Test Steps:**
1. Create visitor
2. Record: 5 page_view, 3 click, 2 form_submit
3. Invoke get_events(event_type='page_view')
4. Verify only 5 page_view events

**Expected Result:** Only matching type returned

**Pass Criteria:** Filter accurate

---

#### TC-EVT-005: Filter Events by Date Range
**Test ID:** TC-EVT-005  
**Priority:** Medium  
**Requirement:** FR-EVT-004

**Test Steps:**
1. Create visitor
2. Record events with known dates
3. Invoke get_events(date_from, date_to)
4. Verify only events in range returned

**Expected Result:** Date range filter works

**Pass Criteria:** Inclusive of boundaries

---

### 2.3 CRM Integration

#### TC-CRM-001: Link Event to Contact
**Test ID:** TC-CRM-001  
**Priority:** High  
**Requirement:** FR-CRM-001

**Test Steps:**
1. Create visitor
2. User logged into FA (contact_id = 5)
3. Record event via tracking endpoint
4. Verify event.contact_id = 5

**Expected Result:** Event linked to logged-in user

**Pass Criteria:** contact_id captured from session

---

#### TC-CRM-002: Customer Activity Summary
**Test ID:** TC-CRM-002  
**Priority:** Medium  
**Requirement:** FR-CRM-002

**Test Steps:**
1. Create customer
2. Create 3 visitors linked to customer
3. Record various events
4. Invoke get_customer_activity(debtor_no)
5. Verify summary includes:
   - Visit count
   - Event count
   - Event breakdown by type

**Expected Result:** Complete activity summary

**Pass Criteria:** All metrics accurate

---

### 2.4 Analytics

#### TC-ANA-001: Get Visitor Statistics
**Test ID:** TC-ANA-001  
**Priority:** High  
**Requirement:** FR-ANA-001

**Test Steps:**
1. Create 10 visitors over past 30 days
2. Create 5 visitors older than 30 days
3. Invoke get_tracking_stats()
4. Verify total_visitors = 15
5. Verify recent_visitors = 10

**Expected Result:** Statistics calculated correctly

**Pass Criteria:** Correct counts for date ranges

---

#### TC-ANA-002: Get Event Type Statistics
**Test ID:** TC-ANA-002  
**Priority:** Medium  
**Requirement:** FR-ANA-002

**Test Steps:**
1. Record events: 20 page_view, 10 click, 5 form_submit
2. Invoke get_event_type_stats()
3. Verify returns correct counts per type

**Expected Result:** Event counts by type

**Pass Criteria:** All types, accurate counts

---

### 2.5 Admin UI

#### TC-UI-001: Visitor List Display
**Test ID:** TC-UI-001  
**Priority:** High  
**Requirement:** FR-UI-001

**Test Steps:**
1. Create 15 visitors with various data
2. Navigate to Visitor Tracking page
3. Verify all visitors displayed
4. Verify columns: Visitor ID, First Visit, Last Visit, Visits, Customer

**Expected Result:** Full list with correct data

**Pass Criteria:** All visitors visible with correct values

---

#### TC-UI-002: Event History Display
**Test ID:** TC-UI-002  
**Priority:** High  
**Requirement:** FR-UI-002

**Test Steps:**
1. Create visitor with events
2. Navigate to visitor detail
3. View Event History section
4. Verify events with type, URL, timestamp

**Expected Result:** Events displayed correctly

**Pass Criteria:** All event data visible

---

#### TC-UI-003: Statistics Dashboard
**Test ID:** TC-UI-003  
**Priority:** Medium  
**Requirement:** FR-UI-003

**Test Steps:**
1. Navigate to Statistics page
2. Verify key metrics displayed
3. Change date range
4. Verify metrics update

**Expected Result:** Dashboard with accurate data

**Pass Criteria:** Metrics accurate, filter works

---

## 3. Test Data Requirements

### 3.1 Required Test Data

| Entity | Quantity | Purpose |
|--------|----------|---------|
| Visitors | 20 | Tracking tests |
| Customers (debtors) | 5 | Linking tests |
| Events | 100 | Event tests |
| Event Types | 5 | Type filter tests |

### 3.2 Test Environment Setup

```sql
-- Insert test customers
INSERT INTO debtors_master (debtor_no, name) VALUES 
(1, 'Test Customer A'),
(2, 'Test Customer B');

-- Insert test visitors
INSERT INTO fa_tracking_visitors (visitor_id, ip_address) VALUES 
('v-test001', '192.168.1.1'),
('v-test002', '192.168.1.2');
```

---

## 4. Pass Criteria Summary

| Category | Pass Criteria |
|----------|---------------|
| Visitor Tracking | Create, update, link all work |
| Event Recording | Track, retrieve, filter all work |
| CRM Integration | Contact linking, customer summary work |
| Analytics | Statistics calculated correctly |
| UI | Pages display correct data |

---

*Document Version: 1.0.0*  
*Last Updated: 2026-05-12*