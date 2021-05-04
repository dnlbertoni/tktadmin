import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { CalendarModule,  } from 'angular-calendar';
import { SharedModule } from '../shared/shared.module';
import { BrowserModule } from '@angular/platform-browser';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { AgmCoreModule } from '@agm/core';

import { TicketsService } from './tickets.service';
import { GestionComponent } from './gestion/gestion.component';
import { WorkonComponent } from './workon/workon.component';
import { CardStatsComponent } from './card-stats/card-stats.component';
import { RouterModule } from '@angular/router';



@NgModule({
  imports: [
    CommonModule,
    RouterModule,    
    FormsModule,
    BrowserModule,
    BrowserAnimationsModule,
    SharedModule,
    AgmCoreModule.forRoot({
      // https://developers.google.com/maps/documentation/javascript/get-api-key?hl=en#key
      apiKey: ''
    }),
    CalendarModule.forRoot()
  ],
  declarations: [

  GestionComponent,
  WorkonComponent,
  CardStatsComponent],

  exports: [

  ],  
  providers: [
    TicketsService
  ]
})
export class TicketsModule { }
