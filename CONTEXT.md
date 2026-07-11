# Catalog

Owns the product catalog: products, their prices and tax. The system of record for product data; other contexts hold projections, never query it synchronously. Part of the [context map](../CONTEXT-MAP.md).

## Language

**Product**:
A sellable catalog entry with a name, price, and tax rate. Identity (`ProductId`) is referenced by other contexts; the data itself is owned here.
_Avoid_: Item, article, SKU (as a synonym for the whole product)
