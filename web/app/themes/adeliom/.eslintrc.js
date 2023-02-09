module.exports = {
    "root": true,
    "env": {
        "browser": true, // Browser global variables like `window` etc.
        "commonjs": true, // CommonJS global variables and CommonJS scoping.Allows require, exports and module.
        "es6": true, // Enable all ECMAScript 6 features except for modules.
        "jest": true, // Jest global variables like `it` etc.
        "node": true // Defines things like process.env when generating through node
    },
    "parser": "@typescript-eslint/parser",
    "plugins": [
        "@typescript-eslint"
    ],
    "extends": [
        "eslint:recommended",
        "plugin:@typescript-eslint/eslint-recommended",
        "plugin:@typescript-eslint/recommended"
    ],
    "rules": {
        "no-unused-vars": 0
    }
}
