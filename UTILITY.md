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

## Permission Checking

### Overview
The `FeatureAccess` helper class provides a standardized way to check user permissions for specific features throughout the application.

### Usage

#### Basic Permission Checks
```php
// Using objects
if (FeatureAccess::canView($user, $feature)) {
    // Show feature content
}

// Using IDs directly
if (FeatureAccess::canViewById(auth()->id(), $featureId)) {
    // Show feature content
}
```
Available Methods
- canView() / canViewById() - Checks if user can view the feature (levels 1-3)
- canCreate() / canCreateById() - Checks if user can create
- canEdit() / canEditById() - Checks if user can edit
- canDelete() / canDeleteById() - Checks if user can delete
- canApprove() / canApproveById() - Checks if user can approve
- getViewLevel() / getViewLevelById() - Gets the user's view level for the feature Blade Template Example
```php
@if(FeatureAccess::canEdit(auth()->user(), $feature))
    {{-- Show edit button --}}
    <button class="btn btn-primary">Edit Feature</button>
@endif

@unless(FeatureAccess::canViewById(auth()->id(), $featureId))
    <div class="alert alert-warning">You don't have view permission</div>
@endunless
 ```
```
 Controller Example
```php
public function edit(AppFeature $feature)
{
    if (!FeatureAccess::canEdit(auth()->user(), $feature)) {
        abort(403, 'Unauthorized action');
    }
    
    return view('features.edit', compact('feature'));
}

public function update(Request $request, AppFeature $feature)
{
    if (!FeatureAccess::canEdit(auth()->user(), $feature)) {
        return back()->with('error', 'Permission denied');
    }
    
    // Update logic here
}
 ```
```

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