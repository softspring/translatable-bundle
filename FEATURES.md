# Translatable Bundle Features

Functional definition for `softspring/translatable-bundle`.

This file defines the expected behavior and functional scope of the bundle.

## Purpose

- Provide a reusable way to store and edit translated field values in Symfony applications.
- Make translated form inputs easier to build with one consistent form API.
- Add optional automatic translation support for editing interfaces.

## Main Features

- Register a Doctrine DBAL type named `sfs_translation`.
- Provide a `Softspring\TranslatableBundle\Model\Translation` value object for translated field data.
- Provide a Doctrine post-load listener that injects request locale context into loaded translations.
- Provide a `TranslationType` form type for translated text fields.
- Provide a lower-level `TranslatableType` form type for custom translated field structures.
- Guess `TranslationType` automatically for Doctrine fields using the `sfs_translation` type.
- Provide Twig form themes for Bootstrap 5 and Semantic UI.
- Provide a Twig filter named `sfs_translate`.
- Provide an optional API translation layer with a controller and driver abstraction.
- Ship a Google Translate driver for automatic translation.
- Ship JS and CSS assets for the auto-translate UI.

## Translation Model Expectations

- A translation value should store per-locale text values.
- A translation value should keep a default locale.
- When a translation for the requested locale is missing, the value should fall back to the default locale.
- The translation value should serialize cleanly for Doctrine JSON storage.

## Form Expectations

- `TranslationType` should expose a translated text field collection plus `_trans_id`.
- `TranslatableType` should expose one field per configured locale plus `_default`.
- The default locale field should be required.
- Locale-specific field metadata should be available in the form view.
- Form themes should render locale inputs clearly for the supported UI themes.

## API Translation Expectations

- API translation should be optional and disabled unless configured.
- The bundle should expose a POST endpoint for translation requests when the API layer is enabled and routed.
- The API response should return structured status and translated text data.
- The form extension should add translate button metadata only when an API driver is configured.

## Integration Expectations

- The bundle should prepend Doctrine configuration so the custom DBAL type is available.
- Applications should be able to use the provided form theme blocks directly or reuse them from other bundles.
- Applications should be able to import the JS auto-translate behavior when they want interactive translation buttons.
- Applications should be able to swap the translation driver by implementing `TranslatorDriverInterface`.

## Current Limits

- Automatic translation currently only ships with a Google driver.
- The API route still needs to be imported by the application or another bundle.
- The auto-translate UI depends on the provided JS asset to make the buttons work.
- The Doctrine type is optimized for JSON-backed translated fields, not for relational translation tables.
