import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { LoginI } from '../pages/models/login.interface';

@Injectable({
  providedIn: 'root'
})
export class ApiService {

  apiUrl="https://api.avalonsevenmeadows.org"
  constructor(private http: HttpClient) { }

  getResidents()
  {
    return this.http.get<any>(`${this.apiUrl}/parametros`)
  }

  login(form: LoginI){
    return this.http.post(this.apiUrl+"/login",form)
  }


}
