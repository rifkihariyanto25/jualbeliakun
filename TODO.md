# TODO: Add Button for Creating New Account with Skin and Rank Options

## Tasks to Complete

- [x] Create a new migration to alter the accounts table: change `skin_count` column to `skin` (varchar) to store skin types (collector, legend, epic, special).
- [x] Update `app/Models/Account.php`: Change fillable array from 'skin_count' to 'skin'.
- [x] Update `app/Http/Controllers/AccountController.php`: Change validation and store logic from 'skin_count' to 'skin'.
- [x] Update `resources/views/home.blade.php`: Add a button to navigate to the create account page, and change display from skin_count to skin.
- [x] Create content for `resources/views/accounts/create.blade.php`: Build a form with inputs for username, rank (select dropdown with options: mythic, legend, epic, grandmaster), hero_count, skin (select dropdown with options: collector, legend, epic, special), price.
- [x] Update `resources/views/accounts/index.blade.php`: Change display from skin_count to skin.
- [x] Run the new migration to apply database changes.
- [x] Test the functionality by accessing the home page, clicking the add button, filling the form, and verifying the account is created and displayed correctly.
