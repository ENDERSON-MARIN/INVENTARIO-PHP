-- ============================================================================
-- Laravel 5.7 Inventory Management System - Database Triggers
-- ============================================================================
--
-- Purpose: This script creates database triggers for automatic stock management
--          in the productos table. Triggers ensure stock levels are updated
--          automatically when purchase or sale details are inserted or deleted.
--
-- Execution: This script is automatically executed by MySQL Docker container
--            on first startup via the /docker-entrypoint-initdb.d/ mechanism.
--            Files are executed in alphabetical order.
--
-- Naming: 02_triggers_PRODUCTOS.sql (prefix ensures execution after schema)
--
-- Triggers Defined:
--   1. tr_addStockCompra  - Increases stock when purchase detail is added
--   2. tr_delStockCompra  - Decreases stock when purchase detail is deleted
--   3. tr_addStockVenta   - Decreases stock when sale detail is added
--   4. tr_delStockVenta   - Increases stock when sale detail is deleted
--
-- Business Logic:
--   - Purchases (compras) increase product stock
--   - Sales (ventas) decrease product stock
--   - Deletions reverse the stock changes
--
-- Note: These triggers maintain referential integrity and ensure accurate
--       inventory tracking without requiring application-level logic.
--
-- ============================================================================

-- ============================================================================
-- PURCHASE DETAIL TRIGGERS (compra_detalles)
-- ============================================================================

-- Trigger: Increment product stock when a purchase detail is added
-- When: AFTER INSERT on compra_detalles table
-- Action: Increases stock in productos table by the purchased quantity
/*COMPRA_DETALLES */

/*TRIGGER BASE DE DATOS INCREMENTAR STOCK PRODUCTOS CON LOS DETALLES */


CREATE TRIGGER `tr_addStockCompra` AFTER INSERT ON `compra_detalles` FOR EACH ROW BEGIN
		UPDATE productos SET  stock = stock + NEW.cantidad
        WHERE productos.id= NEW.producto_id;

  END


-- Trigger: Decrement product stock when a purchase detail is deleted
-- When: AFTER DELETE on compra_detalles table
-- Action: Decreases stock in productos table by the deleted purchase quantity
-- Note: This reverses the stock increase from tr_addStockCompra
/*TRIGGER BASE DE DATOS DISMINUIR STOCK PRODUCTOS CON LOS DETALLES */


CREATE TRIGGER `tr_delStockCompra` AFTER DELETE ON `compra_detalles` FOR EACH ROW BEGIN
		UPDATE productos SET  stock = stock - old.cantidad
        WHERE productos.id= old.producto_id;

  END


-- ============================================================================
-- SALE DETAIL TRIGGERS (venta_detalles)
-- ============================================================================

-- Trigger: Decrement product stock when a sale detail is added
-- When: AFTER INSERT on venta_detalles table
-- Action: Decreases stock in productos table by the sold quantity
-- Note: Sales reduce inventory, opposite of purchases
  /**************************************************************************************/


  /*VENTA_DETALLES */

/*TRIGGER BASE DE DATOS INCREMENTAR STOCK PRODUCTOS CON LOS DETALLES */


CREATE TRIGGER `tr_addStockVenta` AFTER INSERT ON `venta_detalles` FOR EACH ROW BEGIN
		UPDATE productos SET  stock = stock - NEW.cantidad
        WHERE productos.id= NEW.producto_id;

END


-- Trigger: Increment product stock when a sale detail is deleted
-- When: AFTER DELETE on venta_detalles table
-- Action: Increases stock in productos table by the deleted sale quantity
-- Note: This reverses the stock decrease from tr_addStockVenta (e.g., sale cancellation)
/*TRIGGER BASE DE DATOS DISMINUIR STOCK PRODUCTOS CON LOS DETALLES */


CREATE TRIGGER `tr_delStockVenta` AFTER DELETE ON `venta_detalles` FOR EACH ROW BEGIN
		UPDATE productos SET  stock = stock + old.cantidad
        WHERE productos.id= old.producto_id;

  END



-- ============================================================================
-- End of trigger definitions
-- ============================================================================
