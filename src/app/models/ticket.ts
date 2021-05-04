import { Injectable } from "@angular/core";
import { Adapter } from "../interface/adapter";

export class Ticket {

   constructor(
    public Fecha: string,
    public NroPedido: number,
    public Tipopedido: string,
    public Titulo: string,
    public UsuarioAsignado: string,
    public Estado: string,
    public Link: string,
    public Usuariopotencial: string,
    public Frecuente: number,
    public Complejidad: string,
    public Horasestimadas: number
        ){

    }
}
@Injectable({
  providedIn: "root",
})  
export class TicketAdater implements Adapter<Ticket> {
    adapt(item: any): Ticket {
      return new Ticket(
        item.Fecha, 
        item.NroPedido, 
        item.Tipopedido,
        item.Titulo, 
        item.UsuarioAsignado, 
        item.Estado, 
        item.Link, 
        item.Usuariopotencial, 
        item.Frecuente, 
        item.Complejidad,
        item.Horasestimadas
        );
    }
  }