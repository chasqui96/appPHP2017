CREATE OR REPLACE FUNCTION sp_cobros(
    codigo integer,
    efectivo integer,
    idapercierre integer,
    detallecobro text[],
    cobrocheque text[],
    cobrotarjeta text[],
    operacion integer)
  RETURNS void AS
$BODY$
declare
	cantcuentas integer = array_length(detallecobro,1);
	cantcheques integer = array_length(cobrocheque,1);
	canttarjetas integer = array_length(cobrotarjeta,1);
	ultcod integer = (select coalesce(max(id_cobros),0)+1 from cobros);
	cuentas record;
begin
	if operacion = 1 then
	
		insert into cobros
		values(ultcod, current_date, efectivo, 'PENDIENTE', idapercierre);

		--{{idventa,nrocta,nrofactura,vencimiento,monto}}
		for cta in 1..cantcuentas loop
			insert into detalle_cobros
			values(
				ultcod,
				detallecobro[cta][1]::integer,
				detallecobro[cta][2]::integer,
				detallecobro[cta][5]::integer
			);
			update cuentas_cobrar set cta_saldo = cta_saldo - detallecobro[cta][5]::integer, cta_estado = 'PAGADO'
			where id_venta = detallecobro[cta][1]::integer and cta_nro = detallecobro[cta][2]::integer;
		end loop;

		--{{identidad, entidad, nrocheque, titular, emision, vencimiento, importe}}

		if cantcheques > 0 then
			for ch in 1..cantcheques loop
				insert into cobro_cheques
				values(
					ultcod, 
					cobrocheque[ch][1]::integer, 
					cobrocheque[ch][3]::integer, 
					cobrocheque[ch][5]::date, 
					cobrocheque[ch][6]::date, 
					cobrocheque[ch][7]::integer, 
					cobrocheque[ch][4]::character varying
				);
			end loop;

		end if;
		--{{idtarjeta, tarjeta, identidad, entidad, nrotarjeta, caut, importe}}
		if canttarjetas > 0 then
			for tar in 1..canttarjetas loop
				insert into cobro_tarjetas
				values(
					ultcod,
					cobrotarjeta[tar][1]::integer,
					cobrotarjeta[tar][5]::integer,
					cobrotarjeta[tar][6]::integer,
					cobrotarjeta[tar][3]::integer,
					cobrotarjeta[tar][7]::integer
				);

			end loop;
		end if;
		raise notice 'EL COBRO FUE REGISTRADO CON EXITO';
	end if;

	if operacion = 2 then
		update cobros set cob_estado = 'ANULADO' where id_cobros = codigo;
		--RECORRER LAS CUENTAS COBRADAS PARA ACTUALIZAR SU SALDO (SUMAR)
		for cuentas in select * from detalle_cobros where id_cobros = codigo loop
			update cuentas_cobrar set cta_saldo = cta_saldo + cuentas.detc_monto, cta_estado = 'PENDIENTE'
			where id_venta = cuentas.id_venta and cta_nro = cuentas.cta_nro;
		end loop;
		raise notice 'EL COBRO FUE ANULADO CON EXITO';
	end if;
end
$BODY$
  LANGUAGE plpgsql;


