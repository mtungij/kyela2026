# Members Who Haven't Paid Report Feature

## Overview

A comprehensive **Members Who Haven't Paid Report** (Ambao Hawajalipa) has been added to display all members with outstanding payment balances, sorted by the amount owed.

## Features

✅ **Outstanding Balance Report** - Shows all members with unpaid balances
✅ **Date Range Filtering** - Filter by date range (default: today)
✅ **Summary Statistics** - Total members owing, amount paid, total debt
✅ **Detailed Table** - Member info, amounts, and debt status
✅ **PDF Export** - Download professional reports
✅ **Risk Indicators** - Visual status for debt severity
✅ **Responsive Design** - Mobile, tablet, desktop compatible
✅ **Dark Mode Support** - Full dark theme compatibility
✅ **Quick Links** - Click member names to view full dashboard
✅ **Swahili Localization** - All text in Swahili

## What's Displayed

### Summary Cards

| Card | Shows |
|------|-------|
| **Jumla ya Wanachama** | Total members with outstanding balances |
| **Jumla ya Kulipwa** | Total amount paid so far |
| **Jumla ya Deni** | Total amount owed (outstanding balance) |

### Data Table Columns

| Column | Content |
|--------|---------|
| **Jina la Mwanachama** | Member name (clickable link to dashboard) |
| **Simu** | Member phone number |
| **Jumla ya Deni** | Total debt/outstanding amount |
| **Kulipwa Sasa** | Amount paid to date |
| **Baki** | Remaining balance to pay |
| **Hali** | Risk status (Kuzaliwa Sana, Kuzaliwa Kidogo) |

### Status Badges

| Status | Color | Meaning |
|--------|-------|---------|
| **Kuzaliwa Sana** | Red | Heavily indebted (>50% outstanding) |
| **Kuzaliwa Kidogo** | Yellow | Lightly indebted (<50% outstanding) |

## Files Created/Modified

### New Files

1. **resources/views/unpaid/report.blade.php**
   - Main unpaid report view
   - Date filter form
   - Summary statistics cards
   - Responsive data table
   - Pagination

2. **resources/views/unpaid/pdf.blade.php**
   - PDF template for unpaid reports
   - Professional layout
   - Summary and detailed table
   - Print-friendly styling

### Modified Files

1. **app/Http/Controllers/PaymentReportController.php**
   - Added `unpaidReport()` method - Display unpaid report
   - Added `unpaidDownloadPdf()` method - Generate PDF

2. **routes/web.php**
   - Added `GET /unpaid/report` route
   - Added `GET /unpaid/download-pdf` route

3. **resources/views/components/layouts/app/sidebar.blade.php**
   - Updated "Ambao Hawajalipa" link to point to new report

## How to Access

### Via Sidebar
```
Sidebar → Reports → Ambao Hawajalipa (Those Who Haven't Paid)
```

### Direct URLs
```
View Report: /unpaid/report
Download PDF: /unpaid/download-pdf
```

### With Custom Dates
```
/unpaid/report?from_date=2025-01-01&to_date=2025-01-31
/unpaid/download-pdf?from_date=2025-01-01&to_date=2025-01-31
```

## How It Works

### Data Source

The report pulls data from the **Collections** table:
- `total_amount` - Total debt amount
- `amount_paid` - Amount paid so far
- `balance` - Outstanding amount (total_amount - amount_paid)
- `created_at` - Date filtering (when collection was created)

### Filtering Logic

1. **Default**: Shows today's records
2. **Custom Range**: Filter between any two dates
3. **Query Parameters**: Preserves filters during pagination
4. **Ordering**: Sorted by balance (highest debt first)

### Status Calculation

```
if (balance > total_amount * 0.5) → "Kuzaliwa Sana" (Red)
if (balance > 0 AND balance <= total_amount * 0.5) → "Kuzaliwa Kidogo" (Yellow)
```

## Summary Statistics

### Calculation Details

**Total Members** - Count of collections with balance > 0
**Total Amount Paid** - Sum of all amount_paid for members with debt
**Total Amount Owed** - Sum of all balance (outstanding amounts)

```php
$summary = [
    'total_members' => count of collections with balance > 0,
    'total_amount_paid' => sum of amount_paid,
    'total_amount_owed' => sum of balance,
];
```

## PDF Export

### Features
- Professional formatting
- Date range in header
- Summary statistics section
- Detailed table with all members
- Footer with timestamp
- Print-ready styling

### File Naming
```
Ripoti_Ya_Hawajalipa_YYYY-MM-DD_YYYY-MM-DD.pdf
```

Example: `Ripoti_Ya_Hawajalipa_2025-01-01_2025-01-31.pdf`

## Responsive Design

### Mobile (< 768px)
- Single column layout
- Stacked filter form
- Summary cards stack vertically
- Table scrolls horizontally

### Tablet (768px - 1024px)
- Two-column filter form
- Summary cards in row
- Table responsive

### Desktop (> 1024px)
- Three-column filter form
- Full width display
- Optimized table layout

## Dark Mode Support

✅ Automatic theme detection
✅ Red color scheme (danger/warning)
✅ Proper contrast for accessibility
✅ All text colors adjusted

## Color Scheme

- **Header**: Red (#dc2626 / #f87171)
- **Total Amount**: Gray (#333)
- **Paid Amount**: Blue (#2563eb)
- **Outstanding**: Red (#dc2626)
- **Heavily Indebted**: Dark Red (#991b1b)
- **Lightly Indebted**: Yellow (#eab308)
- **Background**: White/Dark Gray

## Pagination

- **Items per page**: 15
- **Query preservation**: Maintains filter parameters
- **Easy navigation**: Previous/Next buttons
- **Sorted by debt**: Highest debt first

## Performance Considerations

- Efficient query with pagination
- Only calculates for collections with balance > 0
- Minimal database overhead
- Fast PDF generation
- Indexed queries on balance field recommended

## Swahili Text Reference

| English | Swahili |
|---------|---------|
| Members Who Haven't Paid | Ambao Hawajalipa |
| Debt Report | Deni Limepaka |
| Total Members | Jumla ya Wanachama |
| Total Amount Owed | Jumla ya Deni |
| Total Paid | Jumla ya Kulipwa |
| Heavily Indebted | Kuzaliwa Sana |
| Lightly Indebted | Kuzaliwa Kidogo |
| Member Name | Jina la Mwanachama |
| Phone | Simu |
| Total Debt | Jumla ya Deni |
| Amount Paid | Kulipwa |
| Balance/Remaining | Baki |
| Status | Hali |
| Everyone has paid | Wagote wote wamefanikisha Malipo |

## Testing Checklist

- [ ] Navigate to "Ambao Hawajalipa" in sidebar
- [ ] Verify records with outstanding balance display
- [ ] Change date range and filter
- [ ] Verify correct data displays (highest debt first)
- [ ] Click member names (should go to payment dashboard)
- [ ] Verify status badges are correct
- [ ] Download PDF and verify content
- [ ] Test on mobile device
- [ ] Test dark mode
- [ ] Verify pagination (if > 15 items)
- [ ] Check all text is in Swahili
- [ ] Verify amounts formatted correctly

## Use Cases

### Daily Follow-up
- Check members with outstanding payments
- Prioritize heavily indebted members
- Send reminders based on status

### Monthly Reconciliation
- Generate report of all outstanding debts
- Track payment progress
- Export for accounting records

### Member Review
- View individual member debt status
- Compare payment progress
- Plan payment collection strategy

### Collections Management
- Identify problem accounts
- Monitor payment trends
- Report to leadership

## Integration with Existing Features

### Links to:
- **Payment Dashboard** - Click member name to view full payment history
- **Collection Details** - View complete member collection records

### Data Used:
- **Member Model** - Name and phone
- **Collection Model** - Amount owed and payment status

### Related Reports:
- "Ambao Wamelipa" - Those who paid regularly
- "Ambao Waliolipa Faini" - Those who paid penalties
- Collection Dashboard - Individual member details

## Future Enhancements

Possible improvements:
- Add collection method (SMS, email notification)
- Track payment commitment/promises
- Add payment plan tracking
- Monthly reminder automation
- Export to Excel format
- Email report delivery
- Payment arrangement tracking
- Late payment escalation alerts

## Report Frequency

Recommended usage:
- **Daily**: Quick check of new outstanding members
- **Weekly**: Monitor payment progress
- **Monthly**: Formal reconciliation and archiving
- **Quarterly**: Trend analysis and reporting

---

**Created:** January 4, 2026
**Status:** Complete and Ready to Use ✅
**Version:** 1.0.0
