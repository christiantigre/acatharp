import React, { Component } from 'react'



class Login extends Component {

    constructor(props) {
        super(props);
        this.state = {
            empresa: '',
            local: '',
        }
        const options = [
            { value: 'chocolate', label: 'Chocolate' },
            { value: 'strawberry', label: 'Strawberry' },
            { value: 'vanilla', label: 'Vanilla' },
          ];
        console.log(option);
          
          
    }
    
    
    onSubmit(e) {
        e.preventDefault();        
    }


    render() {
        let error = this.state.err;
        let msg = (!error) ? 'Login correcto.' : 'Credenciales incorrectos.';
        let name = (!error) ? 'alert alert-success' : 'alert alert-danger';
        return (
            <div className="py-4" >
                <div className="container">
                    <div className="row justify-content-center">
                        <div className="col-md-8">
                            <div className="card">
                                <div className="card-header">{'Selecciona'}</div>

                                <div className="card-body">
                                    <div className="col-md-offset-2 col-md-12 col-md-offset-2">
                                        {error != undefined && <div className={name} role="alert">{msg}</div>}
                                    </div>
                                    <form className="form-horizontal" role="form" method="POST" onSubmit={this.onSubmit.bind(this)}>
                                        <div className="form-group row">
                                            <label className="col-md-4 col-form-label text-md-right">Empresa</label>

                                            <div className="col-md-6">
                                            <div id="react-search"></div>
                                            </div>
                                        </div>

                                        <div className="form-group row">
                                            <label className="col-md-4 col-form-label text-md-right">Local</label>

                                            <div className="col-md-6">                                            
                                                dato
                                            </div>
                                        </div>

                                       

                                        <div className="form-group row">
                                            <div className="col-md-8 col-md-offset-4">


                                                <button type="submit" className="btn btn-primary">
                                                    Ingresar
                                                </button>

                                                
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        );
    }
}

export default Login;