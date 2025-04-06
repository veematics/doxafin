# Utility Documentation

## Toast Notifications

### Overview
The Toast Notification system provides a unified way to display feedback messages to users. It supports three types of notifications: success, warning, and error.

### Usage

1. Initialize Toast (Already done globally)
```javascript
// Toast is automatically initialized as window.toast
2. Show Notifications
```javascript
// Success message
window.toast.show('Operation completed successfully', 'success');

// Warning message
window.toast.show('Please check your input', 'warning');

// Error message
window.toast.show('An error occurred', 'error');
 ```

### Features
- Three notification types with distinct colors and icons
  - Success (Green with checkmark)
  - Warning (Yellow with warning icon)
  - Error (Red with X icon)
- Auto-dismissible
- Bottom-right positioning
- White text for better visibility
- Responsive design
### Example Implementation
```javascript
try {
    const response = await fetch('/api/endpoint');
    const data = await response.json();
    
    if (response.ok) {
        window.toast.show(data.message, 'success');
    } else {
        window.toast.show(data.error, 'error');
    }
} catch (error) {
    window.toast.show('An error occurred', 'error');
}
 ```

## Date Filter Component
### Overview
The Date Filter component provides a standardized way to filter data by date range across the application.

### Usage
1. Include the Component
```php
<x-date-filter
    :start-date="request('start_date')"
    :end-date="request('end_date')"
/>
 ```

2. Available Props
- start-date : Initial start date value
- end-date : Initial end date value
### Features
- Date range selection
- Predefined period options:
  - Today
  - Yesterday
  - Last 7 Days
  - Last 30 Days
  - This Month
  - Last Month
- Custom date range selection
- Automatic form submission on selection
- Maintains other existing query parameters
### Example Implementation
```php
<form id="filterForm" action="{{ route('current.route') }}" method="GET">
    <x-date-filter
        :start-date="request('start_date')"
        :end-date="request('end_date')"
    />
    <!-- Other filters can be added here -->
</form>
 ```

### Query Usage
```php
// In your controller
$query->whereBetween('created_at', [
    request('start_date', now()->startOfMonth()),
    request('end_date', now()->endOfMonth())
]);
 ```