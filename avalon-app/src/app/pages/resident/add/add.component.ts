import { Component, OnInit } from '@angular/core';
import { FormGroup } from '@angular/forms';
import { ApiService } from 'src/app/services/api.service';

@Component({
  selector: 'app-add',
  templateUrl: './add.component.html',
  styleUrls: ['./add.component.css']
})
export class AddComponent implements OnInit {
  myForm: FormGroup | any;
  residents!:any;

  /**
   *
   */
  constructor(private apiServices: ApiService) {
    

  }
  
  ngOnInit(): void {
    this.getResidents();
  }

  getResidents()
  {
    this.apiServices.getResidents().subscribe((resp)=>{
      debugger;
      console.log("Residensts",resp);
      
      this.residents = resp;

    })
  }

  onSubmit() {}
  
}
