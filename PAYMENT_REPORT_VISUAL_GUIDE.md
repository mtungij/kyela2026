# Payment Report Feature - Visual Guide

## User Interface Components

### 1. Sidebar Navigation
```
Reports
├── Funga Hesabu (Closing Accounts)
├── Ambao Hawajalipa (Those Who Haven't Paid)
├── Ambao Wamelipa ← [NEW] Click this to access payment report
└── Hesabu Za Muda (Temporary Accounts)
```

### 2. Payment Report Dashboard

```
╔════════════════════════════════════════════════════════════╗
║  AMBAO WAMELIPA - RIPOTI YA MALIPO (Those Who Paid Report) ║
╚════════════════════════════════════════════════════════════╝

┌─ FILTER SECTION ──────────────────────────────────────────┐
│                                                             │
│  Tarehe Ya Kuanza     Tarehe Ya Kuishia      [Filter] [PDF]│
│  [From Date Picker]   [To Date Picker]                     │
│                                                             │
└─────────────────────────────────────────────────────────────┘

┌─ SUMMARY CARDS ───────────────────────────────────────────┐
│                                                             │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐    │
│  │ Jumla ya     │  │ Jumla ya     │  │ Jumla ya     │    │
│  │ Malipo       │  │ Kiasi        │  │ Wanachama    │    │
│  │ [Number]     │  │ [Amount]     │  │ [Count]      │    │
│  └──────────────┘  └──────────────┘  └──────────────┘    │
│                                                             │
└─────────────────────────────────────────────────────────────┘

┌─ PAYMENTS TABLE ──────────────────────────────────────────┐
│                                                             │
│ Jina      │ Simu    │ Tarehe   │ Kiasi  │ Kumbuka │ User  │
│ ──────────┼─────────┼──────────┼────────┼────────┼──────│
│ Member 1  │ 255... │ 04/01/26 │ 50000 │ Note   │ User1 │
│ Member 2  │ 255... │ 04/01/26 │ 75000 │ Note   │ User2 │
│ Member 3  │ 255... │ 04/01/26 │ 100000│ Note   │ User3 │
│ ...       │ ...    │ ...      │ ...   │ ...    │ ...   │
│                                                             │
│ Page 1 of 5  [Next]                                        │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

### 3. PDF Download Layout

```
┌─────────────────────────────────────────────────┐
│                                                  │
│         RIPOTI YA MALIPO                        │
│         Ambao Wamelipa                          │
│                                                  │
│  Tarehe: 04/01/2026 - 04/01/2026               │
│                                                  │
├─────────────────────────────────────────────────┤
│                                                  │
│  Jumla ya Malipo: 15        Jumla ya Kiasi: 50M│
│  Jumla ya Wanachama: 12                         │
│                                                  │
├─────────────────────────────────────────────────┤
│                                                  │
│  Nambari│Jina        │Simu      │Tarehe  │Kiasi │
│  ───────┼────────────┼──────────┼────────┼──────│
│  1      │Member 1    │2554XXX  │04/01/26│50,000│
│  2      │Member 2    │2554XXX  │04/01/26│75,000│
│  ...    │...         │...      │...     │...   │
│                                                  │
│  Ripoti hii ilizalishwa: 04/01/2026 14:30     │
│  KWS - Sistemu ya Usimamizi wa Michango        │
│                                                  │
└─────────────────────────────────────────────────┘
```

## Data Flow

```
User clicks "Ambao Wamelipa"
           ↓
   Route: payments.report
           ↓
PaymentReportController::index()
           ↓
Query Payments with filters
           ↓
Calculate Summary Stats
           ↓
Pass to report.blade.php
           ↓
Display on Screen
```

### PDF Generation Flow

```
User clicks "PDF" button
           ↓
   Form submits to payments.download-pdf
           ↓
PaymentReportController::downloadPdf()
           ↓
Query Payments with filters
           ↓
Load pdf.blade.php template
           ↓
Use DOMPDF to generate PDF
           ↓
Download PDF file
```

## Filter Options

| Option | Type | Default | Required |
|--------|------|---------|----------|
| from_date | Date | Today | No |
| to_date | Date | Today | No |

## Table Columns Explained

| Column | Swahili Name | Data Source | Format |
|--------|-------------|-------------|--------|
| Name | Jina la Mwanachama | Payment→Member→name | Text |
| Phone | Simu | Payment→Member→phone | Text |
| Date | Tarehe ya Malipo | Payment→payment_date | DD/MM/YYYY |
| Amount | Kiasi | Payment→amount | 0 decimals |
| Notes | Kumbuka | Payment→notes | Text (nullable) |
| User | Alirekodi na | Payment→User→name | Text |

## Interactivity

### Clickable Elements

1. **Member Name** - Links to individual payment dashboard
   - Route: `collections.show` with member ID
   - Allows viewing member's full payment history

2. **Chafya Button** - Filter/Search
   - Applies date range filter
   - Refreshes table

3. **PDF Button** - Download
   - Generates PDF with current filters
   - Downloads as `Ripoti_Ya_Malipo_YYYY-MM-DD_YYYY-MM-DD.pdf`

4. **Pagination Links** - Navigate pages
   - Preserves current filters
   - Shows 15 items per page

## Dark Mode Behavior

All components support dark mode:
- Background colors automatically switch
- Text colors adjust for contrast
- Table rows get alternate dark shading
- Buttons maintain accessibility

## Responsive Behavior

### Mobile (< 768px)
- Filter form stacks vertically
- Summary cards stack vertically
- Table may scroll horizontally
- Sidebar collapses

### Tablet (768px - 1024px)
- Filter form in 2 columns
- Summary cards in row
- Table responsive

### Desktop (> 1024px)
- Filter form in 3 columns
- All elements optimized
- Full-width table display

## Success States

✅ Filter Applied - Table updates with filtered results
✅ PDF Downloaded - File downloads to user's device
✅ Page Navigation - Query string parameters preserved
✅ Member Click - Navigates to member payment dashboard

## Data Validation

- **Date inputs** - HTML5 date validation
- **Amounts** - Always numeric, 0 decimals
- **Members** - Must exist in database
- **Date range** - from_date can be before to_date

## Swahili Translation Reference

| English | Swahili |
|---------|---------|
| Those Who Paid | Ambao Wamelipa |
| Payment Report | Ripoti Ya Malipo |
| From Date | Tarehe Ya Kuanza |
| To Date | Tarehe Ya Kuishia |
| Filter | Chafya |
| Total Payments | Jumla ya Malipo |
| Total Amount | Jumla ya Kiasi |
| Total Members | Jumla ya Wanachama |
| Member Name | Jina la Mwanachama |
| Payment Date | Tarehe ya Malipo |
| Amount | Kiasi |
| Notes | Kumbuka |
| Recorded By | Alirekodi na |
| Phone | Simu |
| No payments found | Hakuna malipo kwenye kipindi hiki |
