# Rhino Content Hierarchy

## Introduction

This module provides a collection of page types to help user create an organised set of assessments (@see rhino-lite)[https://github.com/dnadesign/rhino-lite]

## Requirements

 * SilverStripe ^4
 * dnadesign/rhino-lite ^2

### Installation

`composer require "dnadesign/rhino-content-hierachy"`

## Suggested Hierarchy

There isn't any restrictions on how to use the different page types. However we suggest you nest the pages following this hierarchy:
```
- Account (Rhino Account)
    - Category
        - Capability
            - Module
                - Assessment
```


