# COMP3340 Final Project
Scuffed restaurant website .-.

## Database Design Schemas
**accounts**
|account_id*|username|password|
|---|---|---|

**menu**
|item_id*|item_name|`category`|price|cost|
|---|---|---|---|---|

**orders**
|`order_id*`|item_1|...|item_#|bill|total_cost|
|---|---|---|---|---|---|

**queue**
|queue_num*|`customer_name`|party_size|arrival_time|
|---|---|---|---|

**receipts**
|receipt_num*|date|time|customer_name|order_state|table_num|`order_id*`|
|---|---|---|---|---|---|---|

**reservations**
|reservation_num*|`customer_name`|party_size|date|time|
|---|---|---|---|---|
