import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators} from '@angular/forms';
import { ApiService } from 'src/app/services/api.service';

@Component({
  selector: 'app-tabs',
  templateUrl: './tabs.component.html',
  styleUrls: ['./tabs.component.css']
})
export class TabsComponent implements OnInit {
  myForm: FormGroup | any;
  formData: any = {}; // Objeto para almacenar los datos del formulario
  displayedColumns: string[] = ['Name', 'Address', 'Section', 'Email', 'Primary Phone', 'Resident Type', 'Lease End Date',  'actions'];
  displayedColumnsV: string[] = ['Address', 'Resident', 'TollTag', 'Vehicle Details', 'Update Date',  'actions'];

  // DataSource para la tabla (puedes inicializarlo con una fila vacía)
  dataSource: any;
  dataSourceV: any;
  
  constructor(private fb: FormBuilder,
    private apiServices: ApiService) {

      // Inicializar el formulario con FormBuilder
    this.myForm = this.fb.group({
      // Definir el campo de selección (input select)
      address: ['', Validators.required],
      // Definir el campo de correo electrónico (input email)
      mailingAddress: ['', [Validators.required, Validators.email]]
    });

   }

  ngOnInit(): void {
    this.getResidents();
  }

  getResidents()
  {
    this.apiServices.getResidents().subscribe((resp)=>{
      debugger;
      console.log("Residensts",resp);
      
      this.dataSource = resp.residents;
      this.dataSourceV = resp.vehicles;

    })
  }

  onSubmit() {
    // Verificar si el formulario es válido
    if (this.myForm.valid) {
      // Lógica para manejar el envío del formulario aquí
      console.log(this.formData);
    } else {
      // Marcar los campos como tocados para mostrar los mensajes de error
      Object.values(this.myForm.controls).forEach(control => {
        (control as any).markAsTouched(); // Usa una afirmación de tipo
      });
    }
  }

  addRow() {
    // Duplica la primera fila (la última del dataSource)
    const newRow = { ...this.dataSource[this.dataSource.length - 1] };
    this.dataSource.push(newRow);
  }

}
