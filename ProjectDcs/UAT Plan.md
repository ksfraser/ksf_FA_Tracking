# UAT Plan - ksf_FA_Tracking

## Document Information
| Field | Value |
|-------|-------|
| **Module** | ksf_FA_Tracking |
| **Version** | 1.0.0 |
| **Author** | KSFII Development Team |
| **Date** | 2026-05-12 |

---

## 1. UAT Overview

### 1.1 Purpose

User Acceptance Testing (UAT) validates that the ksf_FA_Tracking module meets business requirements and is ready for production deployment.

### 1.2 UAT Objectives

1. Verify visitor tracking functions correctly
2. Validate event recording with full context
3. Confirm CRM integration (visitor-customer linking)
4. Ensure statistics display accurately
5. Sign-off on module readiness

### 1.3 Success Criteria

| Criterion | Definition |
|-----------|------------|
| **Visitor Tracking** | Visitors tracked with unique IDs |
| **Event Recording** | Events captured with all context |
| **CRM Integration** | Visitors linked to customers correctly |
| **Analytics** | Statistics accurate and timely |
| **UI** | Pages display data correctly |

---

## 2. UAT Scope

### 2.1 In Scope

- Visitor tracking and management
- Event recording (page views, custom events)
- Visitor-customer linking
- Event filtering and retrieval
- Basic statistics
- Admin UI functionality

### 2.2 Out of Scope

- Real-time dashboard
- Advanced analytics
- Email campaign tracking
- Cookie consent management

---

## 3. UAT Scenarios

### 3.1 Visitor Tracking Scenarios

#### UAT-VIS-001: Track New Visitor
**Scenario:** Visitor arrives on website for first time

**Preconditions:**
- Tracking script installed on test website
- Test website accessible

**Test Steps:**
1. Clear browser cookies/local storage
2. Navigate to tracked page on test website
3. Check tracking database for new visitor record
4. Verify visitor_id generated and stored
5. Verify first_visit timestamp set

**Expected Results:**
- Visitor record created
- Unique visitor_id assigned
- IP address and user agent captured

**Pass/Fail Criteria:**
- [PASS] Visitor tracked on first visit
- [FAIL] Visitor not created

---

#### UAT-VIS-002: Track Returning Visitor
**Scenario:** Same visitor returns to website

**Test Steps:**
1. Use same browser with existing visitor cookie
2. Navigate to tracked page
3. Verify visitor record updated
4. Check visit_count incremented
5. Verify last_visit timestamp updated

**Expected Results:**
- visit_count increased by 1
- last_visit = current timestamp
- No duplicate visitor created

**Pass Criteria:** Visit count accurate, timestamp updated

---

#### UAT-VIS-003: View Visitor Details
**Scenario:** Admin views visitor information

**Preconditions:** Visitor exists in system

**Test Steps:**
1. Navigate to CRM > Visitor Tracking
2. Find and click on test visitor
3. Verify displayed information:
   - Visitor ID
   - First visit date/time
   - Last visit date/time
   - Visit count
   - IP address (if captured)

**Expected Results:** All visitor details displayed

**Pass Criteria:** Data accurate and complete

---

### 3.2 Event Tracking Scenarios

#### UAT-EVT-001: Track Page View
**Scenario:** Page view event recorded

**Test Steps:**
1. Navigate to page on tracked website
2. Check fa_tracking_events for new record
3. Verify:
   - event_type = 'page_view'
   - page_url correct
   - referrer captured
   - event_time set

**Expected Results:** Page view event recorded with context

**Pass Criteria:** Event with all context stored

---

#### UAT-EVT-002: Track Custom Event
**Scenario:** Button click tracked as custom event

**Test Steps:**
1. On test website, trigger custom event (button click)
2. Check events table for record
3. Verify:
   - event_type = 'click' (or custom type)
   - event_data contains element info
   - page_url correct

**Expected Results:** Custom event tracked with data

**Pass Criteria:** Event with payload stored correctly

---

#### UAT-EVT-003: View Event History
**Scenario:** Admin reviews visitor's event timeline

**Test Steps:**
1. Navigate to visitor detail page
2. View Event History section
3. Verify events listed with:
   - Event type
   - Page URL
   - Timestamp
   - Expandable event data

**Expected Results:** Complete event timeline

**Pass Criteria:** All events shown with details

---

#### UAT-EVT-004: Filter Events by Type
**Scenario:** Filter event list to show only page views

**Test Steps:**
1. On visitor detail page, Event History section
2. Select event type filter = 'page_view'
3. Click Apply
4. Verify only page_view events shown

**Expected Results:** Filtered to matching type

**Pass Criteria:** Only page_view events displayed

---

### 3.3 CRM Integration Scenarios

#### UAT-CRM-001: Link Visitor to Customer
**Scenario:** Admin associates visitor with customer

**Preconditions:**
- Visitor record exists
- Customer exists in FA

**Test Steps:**
1. Navigate to visitor detail page
2. Click "Link to Customer" button
3. Search for customer "Test Customer A"
4. Select customer
5. Save link
6. Verify visitor.debtor_no = customer.debtor_no

**Expected Results:**
- Visitor linked to customer
- debtor_no stored in visitor record

**Pass Criteria:** Link created and persisted

---

#### UAT-CRM-002: View Customer Activity
**Scenario:** Sales views customer's website activity

**Preconditions:** Customer has linked visitors with activity

**Test Steps:**
1. Navigate to customer in CRM
2. Find "Website Activity" section
3. Verify displayed:
   - Total visits
   - Last visit date
   - Event breakdown
   - Recent activity timeline

**Expected Results:** Complete activity summary for customer

**Pass Criteria:** All activity visible with accurate counts

---

### 3.4 Analytics Scenarios

#### UAT-ANA-001: View Tracking Statistics
**Scenario:** Marketing views overview statistics

**Test Steps:**
1. Navigate to CRM > Visitor Tracking > Statistics
2. Review displayed metrics:
   - Total visitors (last 30 days)
   - Total events
   - Unique visitors
3. Change date range to "Last 7 days"
4. Verify metrics update

**Expected Results:**
- Key metrics displayed
- Date range filter works

**Pass Criteria:** Metrics accurate, filter functional

---

#### UAT-ANA-002: View Event Type Breakdown
**Scenario:** Marketing reviews events by type

**Test Steps:**
1. On Statistics page, view "Events by Type" section
2. Verify counts for:
   - page_view
   - click
   - form_submit
   - (other types if any)

**Expected Results:** Event counts by type accurate

**Pass Criteria:** All types shown with correct counts

---

### 3.5 UI Scenarios

#### UAT-UI-001: Visitor List Navigation
**Scenario:** User navigates visitor list

**Test Steps:**
1. Navigate to Visitor Tracking
2. Verify visitor table with columns
3. Sort by "Last Visit" (click header)
4. Verify sort works
5. Use pagination if >50 visitors

**Expected Results:** List navigable with sorting

**Pass Criteria:** Sorting and pagination work

---

#### UAT-UI-002: Filter Visitors by Date
**Scenario:** Filter visitors by first visit date

**Test Steps:**
1. On Visitor list page
2. Set date filter: last 7 days
3. Click Apply
4. Verify only visitors from last 7 days shown
5. Clear filter
6. Verify all visitors shown

**Expected Results:** Date filter works, clear resets

**Pass Criteria:** Filter accurate, clear functional

---

## 4. Test Environment

### 4.1 Environment Requirements

| Component | Specification |
|-----------|---------------|
| FrontAccounting | Version 2.4 or higher |
| PHP | 8.1+ |
| Database | MySQL with test company |
| Browser | Chrome/Edge latest |
| Test Website | Simple HTML with tracking script |
| User Accounts | Admin, Marketing, Sales |

### 4.2 Test Data Setup

| Data Type | Quantity | Notes |
|-----------|----------|-------|
| Visitors | 20 | Various visit counts |
| Customers | 5 | For linking tests |
| Events | 100 | Mixed types |
| Linked Visitors | 10 | Connected to customers |

---

## 5. UAT Schedule

### 5.1 Timeline

| Phase | Duration | Activities |
|-------|----------|------------|
| Setup | 0.5 day | Environment, test website, data |
| Execution | 1 day | Run all UAT scenarios |
| Defect Resolution | 0.5 day | Fix critical defects |
| Sign-off | 0.5 day | Review, sign-off |

### 5.2 Roles

| Role | Responsibilities |
|------|------------------|
| UAT Lead | Coordinate testing |
| Business Users | Execute scenarios |
| Developer | Support, fix defects |

---

## 6. Sign-off Requirements

### 6.1 Sign-off Criteria

| Category | Requirement | Status |
|----------|-------------|--------|
| Visitor Tracking | Create, update visitors work | ☐ |
| Event Recording | Track, retrieve events work | ☐ |
| CRM Integration | Link visitor-customer works | ☐ |
| Analytics | Statistics accurate | ☐ |
| UI | Pages functional | ☐ |

### 6.2 Sign-off Template

```
Module: ksf_FA_Tracking
Version: 1.0.0
UAT Date: [Date]

Test Results Summary:
- Total Scenarios: [X]
- Passed: [X]
- Failed: [X]

Sign-off Decision: [APPROVED / NOT APPROVED]

Signatories:
Business Owner: _________________ Date: _____
Technical Lead: _________________ Date: _____
```

---

*Document Version: 1.0.0*  
*Last Updated: 2026-05-12*