module.exports = {
	// Include parentheses around a sole arrow function parameter.
	//
	// Valid Options:
	//
	// - 'avoid' - Omit parentheses when possible. Example: `x => x`
	// - 'always' (default) - Always include parentheses. Example: `(x) => x`
	'arrowParens': 'always',

	// Controls the printing of spaces inside array and objects
	'bracketSpacing': true,

	// If true, puts the `>` of a multi-line jsx element at the end of
	// the last line instead of being alone on the next line
	'jsxBracketSameLine': false,

	// Which parser to use. Valid options are 'flow', 'babel',
	// 'typescript', 'css', 'json', 'graphql' and 'markdown'.
	//
	// NOTE: The `parser` option is automatically set by the plug-in
	// (JsPrettier), based on the contents of current file or selection.
	//
	// NOTE: Vue mixes and matches a lot of different types of code, so we
	// explicitly leave this unset, and let Prettier decide the correct parser.
	// This seems to work, but leaving it here as a reminder.
	// 'parser': 'babel',

	// Fit code within this line limit
	'printWidth': 120,

	// (Markdown only) By default, Prettier will wrap markdown text as-is
	// since some services use a linebreak-sensitive renderer, e.g. GitHub
	// comment and BitBucket. In some cases you may want to rely on
	// SublimeText soft wrapping instead, so this option allows you to opt
	// out with 'never'.
	//
	// Valid options:
	//
	// 'always' - Wrap prose if it exceeds the print width.
	// 'never' - Do not wrap prose.
	// 'preserve' (default) - Wrap prose as-is. available in v1.9.0+
	'proseWrap': 'preserve',

	// Prettier can restrict itself to only format files that contain a
	// special comment, called a pragma, at the top of the file. This is
	// very useful when gradually transitioning large, unformatted codebases
	// to prettier.
	'requirePragma': false,

	// Whether to add a semicolon at the end of every line (semi: true), or
	// only at the beginning of lines that may introduce ASI failures (semi: false)
	'semi': true,

	// If true, will use single instead of double quotes
	'singleQuote': true,

	// Controls the printing of trailing commas wherever possible.
	//
	// Valid options:
	//
	// 'none' - No trailing commas
	// 'es5'  - Trailing commas where valid in ES5 (objects, arrays, etc)
	// 'all'  - Trailing commas wherever possible (function arguments)
	'trailingComma': 'es5',

	'useTabs': true,
}
