Pageable & processable DomPdf
====

See `examples/` for usage.

Decorators
----

Using the decorator pattern, the DomPdf class becomes pluggable. This means a plugin can change/add
a little bit of DomPdf logic. Decorating is the backbone of this package.

Pageable
----

See `examples/merger.php`.

Using the `PageableDompdf` decorator, you can have multiple `<body>` tags, to create 1 big PDF from
very different inputs.

Processable
----

See `examples/pager.html` and `examples/red.html`.

Using the `ProcessableDompdf` decorator, you can create pre-processors for DomPdf input. DomPdf has
several very cool features that are hard to implement. You can turn simple input HTML into advanced
DomPdf HTML with a pre-processor.

* `PagerProcessor` - adds tag `<dompdf-pager>` for simple paging, to replace DomPdf's advanced scripting.
* `NoScriptsProcessor` - removes all `<script>` tags from the input, to make all input safe.
