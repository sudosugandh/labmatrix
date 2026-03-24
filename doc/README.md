# HealthMatrix – Complete User Workflow Guide

Step-by-step guide: **online appointment booking**, **lab staff (admin) work**, test orders, sample collection, reporting, and billing.

---

## Table of Contents

1. [User Registration (lab staff)](#1-user-registration-lab-staff)
2. [Book an appointment online (website)](#2-book-an-appointment-online-website)
3. [Admin workflow after an online booking](#3-admin-workflow-after-an-online-booking)
4. [Add Patient (manual)](#4-add-patient-manual)
5. [Create Test Order](#5-create-test-order)
6. [Mark Sample Collected](#6-mark-sample-collected)
7. [Enter Test Results](#7-enter-test-results)
8. [Complete Test & Generate Report](#8-complete-test--generate-report)
9. [Optional: Create Bill & Record Payment](#9-optional-create-bill--record-payment)
10. [Patient Access to Reports](#10-patient-access-to-reports)
11. [Workflow Summary Diagram](#11-workflow-summary-diagram)

---

## 1. User Registration (lab staff)

**Who:** Lab staff (receptionist, technician, admin)  
**Where:** `/register`

### Steps

1. Open the lab website and go to **Register**.
2. Enter:
   - **Name** – full name
   - **Email** – valid, unique email
   - **Password** – minimum 8 characters
   - **Confirm Password**
3. Click **Register**.
4. You are logged in and redirected to the **Dashboard**.

### Notes

- New users get role `user` and are linked to the current lab.
- For email verification, check your inbox.
- If `register` is disabled, log in with existing credentials.

---

## 2. Book an appointment online (website)

**Who:** Patient (or a doctor booking for a patient) — **no staff login required**  
**Where:** `/book-appointment` (often linked from the menu as **Book Appointment**)

**Prerequisites:** **Settings** → **Features** → **Online Booking** enabled for the lab.

### Step-by-step (online)

1. **Open booking**
   - From the lab website, click **Book Appointment** (or go to `/book-appointment`).

2. **Choose booking type**
   - **I’m a Patient** — booking for yourself.
   - **I’m a Doctor** — booking for your patient (doctor details + patient details).

3. **Fill the form**
   - **Patient:** name, phone (required); email, age, gender (optional).
   - **Referring doctor (optional):** for patient bookings — external doctor name/phone if applicable.
   - **Prescription upload (optional):** PDF / image if the lab allows it.
   - **Package (optional):** if the lab offers packages, selecting one can **create test orders automatically** when the booking is saved.
   - **Appointment date & time:** pick a date; choose a **time slot** (if the lab uses **Booking slots**) or enter a time (if slots are not used).
   - **Notes (optional):** special instructions.

4. **Submit**
   - Click **Book Appointment** (or **Sign in** / OTP flows if your deployment adds them later).

5. **Confirmation**
   - You are redirected to **Appointment confirmation** (`/appointment-confirmation/{id}`).
   - Save the **reference number** (appointment ID), review date/time, and use **Print confirmation** if needed.

6. **After booking (patient self-service)**
   - **My appointments** — `/my-appointments`: look up bookings (e.g. by phone) to view or cancel, per lab rules.
   - **Reports** — when lab reports are ready, patients may use **Patient Reports** (`/patient-reports`) as configured.

### What happens in the system (behind the scenes)

- A **patient** record is created or matched (often by phone).
- An **appointment** is created with status **scheduled**, source **public** (or **doctor** for doctor flow).
- If a **package** was selected, **test orders** are often **created automatically** (one per test in the package), usually **pending** sample.
- Lab users may receive an **in-app notification** (“New appointment booked”).
- Optional integrations (e.g. WhatsApp) may run if configured.

---

## 3. Admin workflow after an online booking

**Who:** Lab staff logged into the **admin** area (`/dashboard`, not the public site)  
**Goal:** Confirm the visit, align tests with what was booked, run the lab workflow, and release reports.

### Complete admin GUI walkthrough (step-by-step)

Follow these **exact screen names and controls** in the admin UI (left purple sidebar, main content, top bar).

#### 1. Sign in

1. Open the lab’s **admin** URL (same host as staff use; not the public marketing site if you use a separate domain).
2. Go to **Log in** (`/login`).
3. Enter **Email** and **Password**, then submit.
4. You land on the **Dashboard** (`/dashboard`). The top bar shows the lab name (e.g. **HealthMatrix**), a **notification** bell, and your **user menu** (e.g. **Admin User**).

#### 2. Open the Appointments list

1. In the **left sidebar**, click **Appointments** (menu item under items such as Dashboard, Patients, Tests, Doctors, Test Packages, Technicians).
2. You are on the appointments index (`/appointments`).
3. Use list **filters** (date, status, booking source, etc., as shown on the page) to narrow rows — e.g. status **scheduled**, source **public**, for new website bookings.
4. **Open** the booking you need: click the row or the **View** / detail action for that appointment.

#### 3. Appointment detail page — what you see

The **Appointment Details** page (`/appointments/{id}`) is split into:

- **Top of the main area**
  - Title **Appointment Details** and the appointment **date/time**.
  - Actions: **Edit** (change appointment fields), **Back** (return to the list).
  - A green **success** banner may appear after actions (e.g. *Appointment status updated successfully*).

- **Main column (left/center)**
  - **Appointment information:** weekday date, time, **Status** badge (e.g. Scheduled, Confirmed), **Booking source** badge (e.g. Public Booking), referring doctor block (if any), **Created by** (e.g. Patient / Public Booking).
  - **Patient information:** name, phone, email, age (and gender if captured).
  - Link **View Full Patient Profile →** opens the full patient record (`/patients/{id}`).

- **Right column — Quick Actions** (purple header **Quick Actions**)
  - **Update Status** — **dropdown** labeled *Update Status*. Values: **Scheduled**, **Confirmed**, **Completed**, **Cancelled**, **No Show**. Changing the value **submits the form immediately** (no separate Save on this control).
  - **Create Test Order** — purple button. Goes to **New Test Order** with **Patient** pre-selected for this appointment’s patient (`/orders/create?patient_id=…` when opened from here).
  - **Delete Appointment** — red button; asks for confirmation before removing the appointment.

- **Right column — Timeline** (purple header **Timeline**)
  - **Created** and **Last Updated** timestamps.

#### 4. Confirm the appointment (status)

1. On **Appointment Details**, in **Quick Actions**, open the **Update Status** dropdown.
2. Choose **Confirmed** when the slot and patient details are validated (or your lab’s rule for “confirmed”).
3. The page reloads. A success message confirms the update.

**Automatic test orders (package bookings):** If this appointment has a **package** linked and status just changed **to** **Confirmed** (from something other than Confirmed), the system may **create test orders automatically** (one per test in the package). The success text can include *Test orders created automatically.* If you see that, open **Test Orders** and verify the new rows for this patient — you can skip manual **Create Test Order** for that package’s tests unless you need extras.

**No package:** No automatic orders. Use **Create Test Order** or **Test Orders** → **New Order** (section 5).

#### 5. Open “New Test Order” (two ways)

**A. From the appointment (recommended when coming from a booking)**  
On **Appointment Details**, in **Quick Actions**, click **Create Test Order** → **New Test Order** opens with **Patient** already set to this appointment’s patient (name and phone in the dropdown).

**B. From the Test Orders module**  
1. Sidebar → **Test Orders** (`/orders`).  
2. Top right → **New Order** (purple button).  
3. **New Test Order** (`/orders/create`) opens — choose **Patient** manually if you did not come from an appointment.

#### 6. Fill the “New Test Order” form (full GUI)

Page title: **New Test Order**. Subtitle: *Create a new test order for a patient.* Top right: **← Back to Orders** returns to the orders list.

| Control | What to do |
|--------|------------|
| **Patient \*** | Required. Open the dropdown and select the patient (options show as **Name - phone**, e.g. `arijit - 9089787665`). If you used **Create Test Order** from an appointment, this is usually already selected. |
| **Order Type \*** | Two **radio** options: **Single Test** — one test only. **Test Package** — one package; the system creates one order row per test in that package. |
| **Test \*** (when **Single Test** is selected) | Required. Dropdown **Select Test** — pick the test (name and price appear in the list). |
| **Test Package \*** (when **Test Package** is selected) | Required. Dropdown **Select Package** — pick a package. A small info area can show **Package includes:** N tests and **Total Price** after you select. |
| **Assign Technician** | Shown only on **large lab** setups (`isLargeLab()`). Dropdown; default **No Technician** or pick a technician. |
| **Status** | Dropdown — default **Pending** (typical for new orders). You can set **Sample Collected**, **Processing**, or **Completed** here if your process starts at a later stage; usually leave **Pending** until collection. |
| **Cancel** | Returns to the orders list without saving. |
| **Create Order** | Purple primary button — saves the order(s) and continues the workflow. |

After **Create Order**, find the order under **Test Orders**, open it, and continue with **sample collection → results → report** (sections 6–8).

#### 7. After orders exist (day-of)

1. **Test Orders** — open each order for that patient.
2. **Collect sample** → **Enter results** → **Complete test** → **Report** (sections 6–8).
3. Optional: **Bills & Invoices** → create bill and record payment (section 9).

#### 8. Notifications

- Use the **notification** icon in the top bar for in-app alerts (e.g. new appointment).
- Optional **WhatsApp** / other channels depend on **Settings** and integrations.

---

### Summary (at a glance)

| Step | Where in GUI | Action |
|------|----------------|--------|
| Login | `/login` | Email + password → Dashboard |
| List bookings | Sidebar **Appointments** | Filter → open appointment |
| Confirm | Appointment **Quick Actions** → **Update Status** | e.g. **Confirmed** |
| Auto orders | Same flow | If **package** + first time **Confirmed**, orders may be created automatically |
| Manual order | **Create Test Order** or **Test Orders** → **New Order** | Fill **New Test Order** form → **Create Order** |
| Lab pipeline | **Test Orders** → order detail | Sample → results → report → bill |

### Patient record

- The patient already exists from the online booking. You normally **do not** need **Patients** → **Add Patient** unless you are correcting data.

### Test orders — when to use the form

| Situation | What to do |
|-----------|------------|
| **Package + just confirmed** | Check **Test Orders** for auto-created rows; use **New Test Order** only for **extra** tests. |
| **No package** | **Create Test Order** or **New Order** when the patient arrives or when tests are known. |
| **Extra tests at the lab** | **New Test Order** again (Single Test or another package as needed). |

---

## 4. Add Patient (manual)

**Where:** **Patients** → **Add Patient** (or `/patients/create`)

### Steps

1. Log in and go to **Patients**.
2. Click **Add Patient**.
3. Fill in:
   - **Full Name** (required)
   - **Phone Number** (required)
   - **Email** (optional)
   - **Age** (optional)
   - **Gender** (optional)
   - **Date of Birth** (optional)
   - **Address** (optional)
4. Click **Create Patient**.

### Notes

- Patients are stored per lab.
- If the patient **already booked online**, their record usually exists — search under **Patients** instead of duplicating.

---

## 5. Create Test Order

**Where:** **Test Orders** → **New Order** (`/orders/create`), or **Appointment Details** → **Quick Actions** → **Create Test Order** (patient pre-filled).

**Full click-by-click GUI:** In [§3 Admin workflow](#3-admin-workflow-after-an-online-booking), see **Open “New Test Order” (two ways)** and **Fill the “New Test Order” form (full GUI)** — sidebar navigation, **Single Test** / **Test Package**, **Assign Technician** (large labs), **Status**, **Cancel** / **Create Order**.

### Steps (short)

1. Open **New Test Order** as above.
2. **Patient \*** — select from the dropdown (**Name - phone** format).
3. **Order Type \*** — **Single Test** or **Test Package** (radio).
4. **Test \*** or **Test Package \*** — pick from **Select Test** or **Select Package** (depending on order type).
5. **Assign Technician** — only on large-lab setups; otherwise skip.
6. **Status** — usually **Pending** for a new order.
7. Click **Create Order** (or **Cancel** to leave without saving).

### Notes

- Tests and packages must exist under **Tests** and **Test Packages**.
- **Test Package** creates one order row per test in the package.
- Default **Status** is **Pending**; sample workflow continues in section 6.
- **Online package bookings** may already have orders after **Confirmed** (section 3) — check **Test Orders** before duplicating.

---

## 6. Mark Sample Collected

**Where:** Order detail page (`/orders/{id}`)

### Option A: Quick Action

1. Open the order from **Test Orders**.
2. In **Workflow Progress**, click **Collect Sample** (or similar quick action).
3. Sample is marked collected, and **collected by** and **sample collected at** are set.

### Option B: Manual Status Update

1. On the order detail page, use the status section.
2. Set **Status** to **Sample Collected**.
3. Set **Sample Status** to **Collected**.
4. Save.

### Notes

- Sample must be marked collected before processing.
- `collected_by` is the logged-in user.
- Valid flow: **Pending** → **Sample Collected**.

---

## 7. Enter Test Results

**Where:** Order detail → **Enter Results** (`/orders/{id}/enter-results`)

### Steps

1. Open the order.
2. Click **Enter Results** (or **Edit Results**).
3. Fill in result values for each test parameter.
4. Click **Save Results**.

### Notes

- Parameters come from the test setup.
- Result entry moves the order to **Processing** if it was Pending or Sample Collected.
- Results are used when generating PDF reports.

---

## 8. Complete Test & Generate Report

### Step 8a: Mark Test Complete

**Where:** Order detail page

1. Ensure results are entered.
2. Click **Complete Test** (or similar quick action).
3. The system will:
   - Set status to **Completed**
   - Deduct inventory for the test (if configured)
   - Create a draft report if results exist

### Step 8b: Upload or Generate Report

**Where:** Order detail → **Upload Report** or **Reports** → create report for the order

#### Option 1: Generate PDF from Results

1. Open the order and go to **Upload Report** (or `/reports/create/{order}`).
2. Check **Generate Professional PDF Report**.
3. Optionally use **Preview PDF**.
4. Set **Status** to **Ready** if the report is final.
5. Click **Save Report**.

#### Option 2: Upload External PDF

1. On the report creation page, leave **Generate Professional PDF Report** unchecked.
2. Upload a PDF in **Report File (PDF)**.
3. Enter report name and status.
4. Click **Save Report**.

### Notes

- If a draft report exists, you can open it and update doctor signature, workflow status, etc.
- Status **Ready** can trigger patient notifications.
- Order status becomes **Report Uploaded** when a report is completed.

---

## 9. Optional: Create Bill & Record Payment

**Where:** **Bills** → **Create New Bill** (`/bills/create`)

### Steps

1. Go to **Bills** → **Create New Bill**.
2. Select **Patient**.
3. Choose **Test Orders** to include (only unbilled orders appear).
4. Adjust **Discount** and **Tax** if needed.
5. Add **Notes** (optional).
6. Click **Create Bill**.
7. To record payment, open the bill and use **Add Payment**.

### Notes

- Orders without a bill appear as pending in the bill creation form.
- Bills can include multiple orders.
- Payment methods are configured in **Settings** → **Payments**.

---

## 10. Patient Access to Reports

**Where:** Public report search `/patient-reports`

### Steps for Patients

1. Visit the lab website.
2. Open **Patient Reports** (or the configured link).
3. Enter:
   - **Phone number** (or required identifier)
   - **Date of birth** (or other required field)
4. Click **Search**.
5. View and download the report.

### Notes

- Exact identifiers depend on lab configuration.
- Reports must be **Ready** to appear in public search.
- Patients can also receive report links by SMS/email if enabled.

---

## 11. Workflow Summary Diagram

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                     ONLINE BOOKING → ADMIN → TEST → REPORT                        │
└─────────────────────────────────────────────────────────────────────────────────┘

PUBLIC WEBSITE (no login)
  └── /book-appointment
      ├── Choose: Patient booking OR Doctor booking
      ├── Enter details, optional package / prescription / slots
      ├── Submit → Appointment status: SCHEDULED (source: public/doctor)
      ├── Optional: package → Test orders auto-created (pending)
      └── /appointment-confirmation/{id} → reference ID, print, my-appointments

LAB ADMIN (/dashboard)
  └── Appointments → filter (e.g. public) → open → confirm/complete/cancel
  └── Test Orders
      ├── If package booking: orders may already exist
      └── Else: New Order when patient arrives
  └── Sample → Results → Complete test → Report (PDF/upload) → Ready
  └── Optional: Bills → Payment

PATIENT
  └── /patient-reports → phone + DOB → download (when report ready)

───────────────────────────────────────────────────────────────────────────────────
STAFF-ONLY PATH (no online booking)
  /register → Patients → New Order → … same order workflow as above

APPOINTMENT STATUSES (examples): scheduled, confirmed, completed, cancelled, no_show

ORDER STATUS PROGRESSION:
  pending → sample_collected → processing → completed → report_uploaded

SAMPLE STATUS:  pending → collected → sent_to_lab

REPORT STATUS:  pending → completed
───────────────────────────────────────────────────────────────────────────────────
```

---

## Quick Reference

| Step | Location | Action |
|------|----------|--------|
| Register (staff) | `/register` | Create lab staff account |
| Book online | `/book-appointment` | Patient/doctor books visit; optional package |
| Confirmation | `/appointment-confirmation/{id}` | Reference ID, print |
| My appointments (public) | `/my-appointments` | Lookup / manage booking |
| Admin: after booking | **Appointments** → open row → **Appointment Details** | **Quick Actions**: **Update Status**; **Create Test Order** when no auto orders |
| Add patient (manual) | **Patients** → **Add Patient** | When not from online booking |
| Create order | **Test Orders** → **New Order** or appointment **Create Test Order** | **New Test Order** form: **Single Test** / **Test Package** → **Create Order** |
| Collect sample | Order → **Collect Sample** | Mark sample collected |
| Enter results | Order → **Enter Results** | Enter parameter values |
| Complete test | Order → quick action | Mark test done |
| Upload report | Order → **Upload Report** or **Reports** | Generate or upload PDF |
| Create bill | **Bills** → **Create** | Bill one or more orders |
| Patient reports | `/patient-reports` | Public report search |

---

## Troubleshooting

- **No tests/packages:** Add them under **Tests** and **Test Packages**.
- **Enter Results disabled:** Ensure sample is collected first.
- **Report generation fails:** Ensure test parameters and results are set.
- **Patient not found in reports:** Confirm report status is **Ready** and identifiers match.
- **Online booking missing:** Enable **Online Booking** in **Settings** → **Features**.
- **Slot unavailable:** Pick another slot or date; lab may have **Booking slots** configured under **Booking slots**.
