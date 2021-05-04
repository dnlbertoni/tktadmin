import { Injectable } from '@angular/core';
import { Ticket } from './ticket.model';
import { Observable , Subject} from 'rxjs';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { throwError } from 'rxjs';
import { retry, catchError } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class TicketsService {

  private tickets: Ticket[];
  private tickets$: Subject<Ticket[]> = new Subject<Ticket[]>();
  
  // Define API
  apiURL = 'http://192.168.5.25/modulos/progress/api';

  constructor(private http: HttpClient) { 
    this.tickets = [];
  }
  getTickets$(): Observable<Ticket[]> {
    return this.tickets$.asObservable();
  }

 /*========================================
    CRUD Methods for consuming RESTful API
  =========================================*/

  // Http Options
  httpOptions = {
    headers: new HttpHeaders({
      'Content-Type': 'application/json'
    })
  } 

    // HttpClient API get() method => Obtiene todos los tickets
    getTickets(agente: string = '', estado: string = '', ini:number = 0, fin: number = 25) {
      let campos:string;
      campos = '?agente='+agente+'&estados='+estado+'&pag_ini='+ini+'&pag_fin='+fin;
      return this.http
      .get <Ticket[]> (this.apiURL + '/tkts.php' + campos)
      .pipe(
        retry(1),
        catchError(this.handleError)
      );
    }

   // HttpClient API get() method => Obtiene todos los tickets
   getKanban( ) {
    return this.http
    .get <any[]> (this.apiURL + '/kanban.php')
    .pipe(
      retry(1),
      catchError(this.handleError)
    );
  }    

  getKpi_1( ) {
    let servicio = '?servicio=kpi';
    return this.http
    .get <any[]> (this.apiURL + '/api.php' + servicio )
    .pipe(
      retry(1),
      catchError(this.handleError)
    );
  }   

    // Error handling 
    handleError(error: { error: { message: string; }; status: any; message: any; }) {
      let errorMessage = '';
      if(error.error instanceof ErrorEvent) {
        // Get client-side error
        errorMessage = error.error.message;
      } else {
        // Get server-side error
        errorMessage = `Error Code: ${error.status}\nMessage: ${error.message}`;
      }
      window.alert(errorMessage);
      return throwError(errorMessage);
  }

  agregarTicket(ticket: Ticket) {
    this.tickets.push(ticket);
    this.tickets$.next(this.tickets);
  }

  nuevoTicket(): Ticket {
    return {
      Fecha: '',
      NroPedido: this.tickets.length,
      Tipopedido: '',
      Titulo: '',
      UsuarioAsignado: '',
      Estado: '',
      Link:'',
      Usuariopotencial:'',
      Complejidad:'',
      Horasestimadas:'',
      idproveedor: 1,
      nro_tkt_externo: 0
    };
  }

}