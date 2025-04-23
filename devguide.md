# Development Guide

## Feature Access Protection

### Using FeatureAccess Helper

The `FeatureAccess::check()` method is used to protect access to features based on user permissions.

#### Method Signature
```php
FeatureAccess::check($userId, $featureName, $permission)

Parameters
- $userId : The ID of the current user (usually auth()->id() )
- $featureName : The name of the feature as stored in appfeatures table
- $permission : The permission to check ( can_view , can_create , can_edit , can_delete , can_approve ) Usage Example
```blade
@php
    $permission = App\Helpers\FeatureAccess::check(auth()->id(), 'Clients', 'can_create')
@endphp
@if($permission == 1)
    <a href="{{ route('clients.create') }}" class="btn btn-primary">Add New Client</a>
@endif
 ```
```
 Permission Values
- 1 : User has permission
- 0 : User does not have permission
- null : Feature or permission not found Common Permission Types
- can_view : View access to the feature
- can_create : Create new records
- can_edit : Edit existing records
- can_delete : Delete records
- can_approve : Approve/process records Cache Implementation
Permissions are cached for performance:

- Cache key format: user_permissions_{user_id}
- Cache duration: 1 hour
- Cache is automatically rebuilt when roles or permissions change


```markdown
## UI Components

### CKEditor Component

The application includes a reusable CKEditor component for rich text editing. This component is designed to be used only on specific pages to minimize resource usage.

#### Usage

Include the component on any form where rich text editing is needed:

```blade
<x-ckeditor 
    id="field_id"
    name="field_name"
    label="Field Label"
    :value="$existingValue"
    :required="false"
/>
 ```
```
 Parameters
- id (required): The HTML ID for the textarea
- name (required): The form field name
- value (optional): The initial content for the editor
- label (optional): The field label
- required (optional): Whether the field is required Implementation Details
The component:

- Loads CKEditor from CDN only on pages where it's used
- Includes standard toolbar options (headings, formatting, lists, etc.)
- Properly handles validation errors
- Uses the @once directive to prevent duplicate script loading
When displaying content from CKEditor, use the {!! $content !!} syntax to render the HTML properly.

# Using CKEditor in a View Component
When working with CKEditor in a Laravel Blade view component, you can use the following code to set the content of the editor:

```javascript
// Set the CKEditor content
const editor = ckeditors.get(document.getElementById('editorId'));
editor.setData(content);
 ```
```

Notes on usage:

1. Replace 'editorId' with the actual ID of your CKEditor instance. This should match the id attribute of the textarea in your Blade component.
2. The ckeditors object is a map that stores all CKEditor instances on the page. It's created by the CKEditor initialization script.
3. document.getElementById('editorId') retrieves the textarea element that CKEditor is attached to.
4. ckeditors.get(...) retrieves the CKEditor instance associated with that textarea.
5. editor.setData(content) sets the content of the editor. Replace content with the string you want to set as the editor's content.
6. Make sure this code runs after the CKEditor has been fully initialized. You might want to wrap it in a DOMContentLoaded event listener or call it after the editor initialization promise resolves.
Example usage in your Blade component:

```php
<x-ckeditor 
    id="myEditor"
    name="myEditor"
    height="300px"
    label="{{ __('Editor Label') }}"
/>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const myContent = "This is the content to set";
    const editor = ckeditors.get(document.getElementById('myEditor'));
    if (editor) {
        editor.setData(myContent);
    } else {
        console.error('Editor not found');
    }
});
</script>
@endpush
 ```
```