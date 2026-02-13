-- ============================================================================
-- Laravel 5.7 Inventory Management System - Database Triggers
-- ============================================================================
--
-- Purpose: This script creates database triggers for automatic stock management
--          in the productos table. Triggers ensure stock levels are updated
--          automatically when purchase or sale details are inserted or deleted.
--
-- ============================================================================

USE inventas;

-- Drop existing triggers if they exist
DROP TRIGGER IF EXISTS tr_addStockCompra;
DROP TRIGGER IF EXISTS tr_delStockCompra;
DROP TRIGGER IF EXISTS tr_addStockVenta;
DROP TRIGGER IF EXISTS tr_delStockVenta;

-- ============================================================================
-- PURCHASE DETAIL TRIGGERS (compra_detalles)
-- ============================================================================

DELIMITER $$

-- Trigger: Increment product stock when a purchase detail is added
CREATE TRIGGER tr_addStockCompra
AFTER INSERT ON compra_detalles
FOR EACH ROW
BEGIN
    UPDATE productos
    SET stock = stock + NEW.cantidad
    WHERE productos.id = NEW.producto_id;
END$$

-- Trigger: Decrement product stock when a purchase detail is deleted
CREATE TRIGGER tr_delStockCompra
AFTER DELETE ON compra_detalles
FOR EACH ROW
BEGIN
    UPDATE productos
    SET stock = stock - OLD.cantidad
    WHERE productos.id = OLD.producto_id;
END$$

-- ============================================================================
-- SALE DETAIL TRIGGERS (venta_detalles)
-- ============================================================================

-- Trigger: Decrement product stock when a sale detail is added
CREATE TRIGGER tr_addStockVenta
AFTER INSERT ON venta_detalles
FOR EACH ROW
BEGIN
    UPDATE productos
    SET stock = stock - NEW.cantidad
    WHERE productos.id = NEW.producto_id;
END$$

-- Trigger: Increment product stock when a sale detail is deleted
CREATE TRIGGER tr_delStockVenta
AFTER DELETE ON venta_detalles
FOR EACH ROW
BEGIN
    UPDATE productos
    SET stock = stock + OLD.cantidad
    WHERE productos.id = OLD.producto_id;
END$$

DELIMITER ;

-- ============================================================================
-- End of trigger definitions
-- ============================================================================
