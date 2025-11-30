# USOS - Unit Systematic Ordering System

## Overview
The USOS (Unit Systematic Ordering System) is an automated inventory management feature that helps track product ordering, delivery schedules, and stock replenishment cycles for customers.

## Features

### 1. **Configuration Management**
- Select customer from database
- Set order date using date picker
- Define total quantity ordered
- Specify monthly usage (automatically calculates daily usage)
- Set production lead time in days

### 2. **Automated Schedule Generation**
The system automatically generates a delivery schedule table with the following columns:
- **No**: Sequential number
- **Order Date**: When the order should be placed
- **Arrival Date**: Expected delivery date (Order Date + Production Lead Time)
- **Run Out Date**: When stock will be depleted (Arrival Date + (Order Qty / Daily Usage))
- **Actual Arrival**: Input field to record actual delivery date
- **Status**: Checklist showing Pending or Completed

### 3. **Automatic Cycle Creation**
When you input an **Actual Arrival Date**:
1. The current schedule entry is marked as completed
2. A new schedule entry is automatically created underneath
3. Next order date is calculated to ensure stock doesn't run out
4. The cycle continues indefinitely

## Database Structure

### Table: `usos_config`
Stores the main configuration for each customer's ordering system.

| Field | Type | Description |
|-------|------|-------------|
| usos_id | INT | Primary key |
| customer_id | INT | Foreign key to customer table |
| order_date | DATE | Initial order date |
| total_quantity_ordered | DECIMAL | Quantity per order |
| monthly_usage | DECIMAL | Usage per month |
| daily_usage | DECIMAL | Auto-calculated (monthly_usage / 30) |
| production_lead_time_days | INT | Days to produce and deliver |

### Table: `usos_schedule`
Tracks each delivery cycle for a configuration.

| Field | Type | Description |
|-------|------|-------------|
| schedule_id | INT | Primary key |
| usos_id | INT | Foreign key to usos_config |
| order_date | DATE | When to place order |
| arrival_date | DATE | Expected arrival |
| run_out_date | DATE | When stock runs out |
| actual_arrival_date | DATE | Actual delivery date (nullable) |
| is_completed | BOOLEAN | Completion status |

## Usage Flow

1. **Create Configuration**
   - Navigate to Product and Order → USOS System
   - Click "Show Form"
   - Fill in all required fields
   - Submit to create initial schedule entry

2. **Track Deliveries**
   - View the schedule table for each configuration
   - When delivery arrives, input the actual arrival date
   - System automatically creates next schedule entry

3. **Manage Configurations**
   - Edit existing configurations
   - Delete configurations (soft delete)
   - View statistics (daily usage, lead time, etc.)

## Calculations

### Daily Usage
```
Daily Usage = Monthly Usage / 30
```

### Arrival Date
```
Arrival Date = Order Date + Production Lead Time (days)
```

### Run Out Date
```
Days Until Runout = Total Quantity Ordered / Daily Usage
Run Out Date = Arrival Date + Days Until Runout
```

### Next Order Date
```
Next Order Date = Run Out Date - Production Lead Time
```

This ensures orders are placed with enough time to arrive before stock runs out.

## Files Created

1. **Database Migration**: `database/migrations/create_usos_tables.sql`
2. **Backend Logic**: `admin/private/usos-backend.php`
3. **Frontend Interface**: `admin/public/usos-manage.php`
4. **Sidebar Update**: Modified `admin/include/sidebar.php`

## Access
Navigate to: **Product and Order → USOS System** from the sidebar menu.
