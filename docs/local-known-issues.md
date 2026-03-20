# Local Known Issues

During clone on macOS, Git reported a case-collision warning for these files:

- `wp-content/fonts/kanit/nKKZ-Go6G5tXcraBGwCYdA.woff2`
- `wp-content/fonts/kanit/nKKZ-Go6G5tXcrabGwCYdA.woff2`

On a case-insensitive macOS filesystem, only one of these paths can exist at a time. This should be cleaned up later in the repository so local clones do not hit the collision.
