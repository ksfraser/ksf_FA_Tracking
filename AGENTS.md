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

## Development Workflow

All development is done in the **devel tree** (`~/Documents/ksf_FA_Tracking`). Do **not** edit files in the UAT bind point directly.

### Workflow Steps
1. **Develop** in this repo (feature branches preferred)
2. **Test**: run repo-appropriate tests
3. **Lint**: `php -l` on modified PHP files (no syntax errors)
4. **Commit** and **Push** branch to GitHub
5. **Merge** to `master` when ready
6. **Push** `master` to GitHub
7. **Deploy** to UAT by pulling in the Infrastructure bind point:

   ```
   cd ~/ksf_Infrastructure/fa_modules/ksf_FA_Tracking
   git stash -u
   git pull origin master
   git stash pop
   ```

### UAT Bind Point
| Path | Purpose |
|------|---------|
| `~/Documents/ksf_FA_Tracking` | Devel tree — all development, testing, commits |
| `~/ksf_Infrastructure/fa_modules/ksf_FA_Tracking` | UAT bind point — deployment target, integration testing (if mirrored) |

