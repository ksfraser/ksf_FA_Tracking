# AGENTS.md - ksf_FA_Tracking#

## Architecture Overview#

**FA Module** for Time/Expense Tracking - project time, expenses, and billing integration.

### Core Principles#
- **SOLID**, **DRY**, **TDD**, **DI**, **SRP**#

## Repository Structure#

```
ksf_FA_Tracking/
├── sql/#
│   ├── fa_tracking_time.sql#
│   ├── fa_tracking_expenses.sql#
│   └── fa_tracking_billing.sql#
├── includes/#
│   ├── time_db.inc#
│   ├── expenses_db.inc#
│   └── billing_db.inc#
├── pages/#
├── hooks.php#
├── composer.json#
└── ProjectDocs/#
```

## Dependencies#

- **ksf_FA_Tracking_Core** (business logic)#
- **ksf_FA_HRM** (link to employees)#
- **ksf_FA_ProjectManagement** (link to projects/tasks)#
- **FrontAccounting 2.4+**#
