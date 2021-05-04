import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { throwError } from 'rxjs';
/*import { Ticket, TicketAdater } from '../models/ticket';*/
/*import { Observable, throwError } from 'rxjs';*/
import { retry, catchError } from 'rxjs/operators';
/*import { map } from "rxjs/operators";*/


@Injectable({
  providedIn: 'root'
})
export class TicketService {

  
  // Define API
  apiURL = 'http://192.168.5.25/modulos/progress/api';

  constructor(private http: HttpClient
    /*,private adapter:TicketAdater*/
  
    ) { }

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
    getTickets() {
      return this.http
      .get(this.apiURL + '/tkts.php')
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

}
