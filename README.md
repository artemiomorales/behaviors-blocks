### Behaviors Blocks

An early prototype that uses attributes to create Interactivity API directives using Block Bindings metadata syntax.

This contains two blocks:
- Behavior Block
- Synced Paragraph

The Behavior Block takes three arguments as part of its `metadata.bindings.content.args` object:
- `directive` - the directive to attach, like 'click' or 'wheel'
- `target_field` - the post meta field the action will target
- `action` - the action to be performed on the post meta field.

The Synced Paragraph is just a block that is meant to connect to post meta, but also polls the REST API at regular intervals to sync the value in the published content with the value on the server.

The demo blocks below demonstrate how you can create a Behavior Block with a click directive to increment a like count, and have the Synced Paragraph update accordingly.

Obviously this is a contrived example, but you could wire this up to be a real like count or deal with other kinds of data (forms, user data) in useful ways.

```
<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:paragraph -->
<p>Like Count:</p>
<!-- /wp:paragraph -->

<!-- wp:interactivity/synced-paragraph {"metadata":{"bindings":{"content":{"source":"core/post-meta","args":{"key":"like_count"}}}}} /-->

<!-- wp:interactivity/behavior-block {"metadata":{"bindings":{"content":{"source":"interactivity/behaviors-options","args":{"directive":"click","target_field":"like_count","action":"increment"}}}},"className":"wp-block-interactivity-behaviors-blocks wp-block-interactivity-behaviors-block"} -->
<div class="wp-block-interactivity-behavior-block wp-block-interactivity-behaviors-blocks wp-block-interactivity-behaviors-block"><!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Increment</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:interactivity/behavior-block --></div>
<!-- /wp:group -->
```