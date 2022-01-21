/*COMPRA_DETALLES */

/*TRIGGER BASE DE DATOS INCREMENTAR STOCK PRODUCTOS CON LOS DETALLES */


CREATE TRIGGER `tr_addStockCompra` AFTER INSERT ON `compra_detalles` FOR EACH ROW BEGIN
		UPDATE productos SET  stock = stock + NEW.cantidad
        WHERE productos.id= NEW.producto_id;

  END


/*TRIGGER BASE DE DATOS DISMINUIR STOCK PRODUCTOS CON LOS DETALLES */


CREATE TRIGGER `tr_delStockCompra` AFTER DELETE ON `compra_detalles` FOR EACH ROW BEGIN
		UPDATE productos SET  stock = stock - old.cantidad
        WHERE productos.id= old.producto_id;

  END


  /**************************************************************************************/


  /*VENTA_DETALLES */

/*TRIGGER BASE DE DATOS INCREMENTAR STOCK PRODUCTOS CON LOS DETALLES */


CREATE TRIGGER `tr_addStockVenta` AFTER INSERT ON `venta_detalles` FOR EACH ROW BEGIN
		UPDATE productos SET  stock = stock - NEW.cantidad
        WHERE productos.id= NEW.producto_id;

END


/*TRIGGER BASE DE DATOS DISMINUIR STOCK PRODUCTOS CON LOS DETALLES */


CREATE TRIGGER `tr_delStockVenta` AFTER DELETE ON `venta_detalles` FOR EACH ROW BEGIN
		UPDATE productos SET  stock = stock + old.cantidad
        WHERE productos.id= old.producto_id;

  END


