document.addEventListener('DOMContentLoaded', function() {
    const registroVisitanteForm = document.getElementById('registro-visitante-form');
    const estadoSelect = document.getElementById('estado_id'); 
    const municipioSelect = document.getElementById('municipio_id'); 
    const parroquiaSelect = document.getElementById('parroquia_id'); 
    const sectorSelect = document.getElementById('sector_id');
    const alcaldiaSelect = document.getElementById('alcaldia_id');
    const circuitoComunalSelect = document.getElementById('circuito_comunal_id');
    const comunaSelect = document.getElementById('comuna_id');
    const consejoComunalSelect = document.getElementById('consejo_comunal_id');
    const ctuSelect = document.getElementById('ctu_id');
    const categoriaVisitaSelect = document.getElementById('categoria_visita_id'); 
    const motivoVisitaInput = document.getElementById('motivo_visita');
    const cedulaInput = document.getElementById('cedula_identidad');
    const nombreInput = document.getElementById('nombre_visitante');
    const apellidoInput = document.getElementById('apellido_visitante');
    const telefonoInput = document.getElementById('telefono_visitante');
    const direccionInput = document.getElementById('direccion_visitante');
    // Cargar estados al iniciar la página
    cargarEstados();


    // Función para buscar un visitante por cédula
    function buscarVisitantePorCedula(cedula) {
        fetch(`./api/buscar_visitante.php?cedula=${cedula}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error HTTP! estado: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    alert(data.error); // Visitante no encontrado
                } else {
                    llenarCamposDireccion(data); // Llenar los campos del formulario
                }
            })
            .catch(error => {
                console.error('Error al buscar el visitante:', error);
                alert('Error al buscar el visitante.');
            });
    }

    // Event listener para buscar visitante al cambiar la cédula
    cedulaInput.addEventListener('blur', function() {
        const cedula = cedulaInput.value;
        if (cedula) {
            buscarVisitantePorCedula(cedula);
        }
    });

    function llenarCamposDireccion(data) {
        // Llenar campos básicos
        document.getElementById('nombre_visitante').value = data.nombre || '';
        document.getElementById('apellido_visitante').value = data.apellido || '';
        document.getElementById('telefono_visitante').value = data.telefono || '';
        document.getElementById('direccion_visitante').value = data.direccion || '';
    
        // Llenar campos de dirección
        const estadoId = data.estado_id || '';
        const municipioId = data.municipio_id || '';
        const parroquiaId = data.parroquia_id || '';
        const sectorId = data.sector_id || '';
        const alcaldiaId = data.alcaldia_id || '';
        const circuitoComunalId = data.circuito_comunal_id || '';
        const comunaId = data.comuna_id || '';
        const consejoComunalId = data.consejo_comunal_id || '';
        const ctuId = data.ctu_id || '';
    
        // Asignar valores a los selects
        document.getElementById('estado_id').value = estadoId;
    
        // Cargar datos dependientes en cascada
        if (estadoId) {
            cargarMunicipios(estadoId).then(() => {
                document.getElementById('municipio_id').value = municipioId;
    
                if (municipioId) {
                    // Cargar parroquias y sectores
                    cargarParroquias(municipioId).then(() => {
                        document.getElementById('parroquia_id').value = parroquiaId;
    
                        if (parroquiaId) {
                            cargarSectores(parroquiaId).then(() => {
                                document.getElementById('sector_id').value = sectorId;
                            });
                        }
                    });
    
                    // Cargar alcaldías, circuitos comunales, comunas y consejos comunales
                    cargarAlcaldias(municipioId).then(() => {
                        document.getElementById('alcaldia_id').value = alcaldiaId;
    
                        if (alcaldiaId) {
                            cargarCircuitosComunales(alcaldiaId).then(() => {
                                document.getElementById('circuito_comunal_id').value = circuitoComunalId;
    
                                if (circuitoComunalId) {
                                    cargarComunas(circuitoComunalId).then(() => {
                                        document.getElementById('comuna_id').value = comunaId;
    
                                        if (comunaId) {
                                            cargarConsejosComunales(comunaId).then(() => {
                                                document.getElementById('consejo_comunal_id').value = consejoComunalId;
                                            });
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
        }
    
        // Cargar CTU (no depende de otras selecciones)
        cargarCTU().then(() => {
            document.getElementById('ctu_id').value = ctuId;
        });
    }

    // Resto del código (cargar estados, municipios, etc.)
    function cargarEstados() {
        return fetch('./api/get_estados.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error HTTP! estado: ${response.status}`);
                }
                return response.json();
            })
            .then(estados => {
                const estadoSelect = document.getElementById('estado_id');
                estadoSelect.innerHTML = '<option value="">Selecciona un estado</option>';
                estados.forEach(estado => {
                    const option = document.createElement('option');
                    option.value = estado.estado_id;
                    option.textContent = estado.nombre_estado;
                    estadoSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error al cargar estados:', error);
            });
    }

    function cargarMunicipios(estadoId) {
        return fetch(`./api/get_municipios.php?estado_id=${estadoId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error HTTP! estado: ${response.status}`);
                }
                return response.json();
            })
            .then(municipios => {
                const municipioSelect = document.getElementById('municipio_id');
                municipioSelect.innerHTML = '<option value="">Selecciona un municipio</option>';
                municipios.forEach(municipio => {
                    const option = document.createElement('option');
                    option.value = municipio.municipio_id;
                    option.textContent = municipio.nombre_municipio;
                    municipioSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error al cargar municipios:', error);
            });
    }

    function cargarParroquias(municipioId) {
        return fetch(`./api/get_parroquias.php?municipio_id=${municipioId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error HTTP! estado: ${response.status}`);
                }
                return response.json();
            })
            .then(parroquias => {
                const parroquiaSelect = document.getElementById('parroquia_id');
                parroquiaSelect.innerHTML = '<option value="">Selecciona una parroquia</option>';
                parroquias.forEach(parroquia => {
                    const option = document.createElement('option');
                    option.value = parroquia.parroquia_id;
                    option.textContent = parroquia.nombre_parroquia;
                    parroquiaSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error al cargar parroquias:', error);
            });
    }

    function cargarSectores(parroquiaId) {
        return fetch(`./api/get_sectores.php?parroquia_id=${parroquiaId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error HTTP! estado: ${response.status}`);
                }
                return response.json();
            })
            .then(sectores => {
                const sectorSelect = document.getElementById('sector_id');
                sectorSelect.innerHTML = '<option value="">Selecciona un sector (opcional)</option>';
                sectores.forEach(sector => {
                    const option = document.createElement('option');
                    option.value = sector.sector_id;
                    option.textContent = sector.nombre_sector;
                    sectorSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error al cargar sectores:', error);
            });
    }

    function cargarAlcaldias(municipioId) {
        return fetch(`./api/get_alcaldias.php?municipio_id=${municipioId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error HTTP! estado: ${response.status}`);
                }
                return response.json();
            })
            .then(alcaldias => {
                const alcaldiaSelect = document.getElementById('alcaldia_id');
                alcaldiaSelect.innerHTML = '<option value="">Selecciona una alcaldía (opcional)</option>';
                alcaldias.forEach(alcaldia => {
                    const option = document.createElement('option');
                    option.value = alcaldia.alcaldia_id;
                    option.textContent = alcaldia.nombre_alcaldia;
                    alcaldiaSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error al cargar alcaldías:', error);
            });
    }

    function cargarCircuitosComunales(alcaldiaId) {
        return fetch(`./api/get_circuitos_comunales.php?alcaldia_id=${alcaldiaId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error HTTP! estado: ${response.status}`);
                }
                return response.json();
            })
            .then(circuitos => {
                const circuitoSelect = document.getElementById('circuito_comunal_id');
                circuitoSelect.innerHTML = '<option value="">Selecciona un circuito comunal (opcional)</option>';
                circuitos.forEach(circuito => {
                    const option = document.createElement('option');
                    option.value = circuito.circuito_comunal_id;
                    option.textContent = circuito.nombre_circuito_comunal;
                    circuitoSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error al cargar circuitos comunales:', error);
            });
    }

    function cargarComunas(circuitoComunalId) {
        return fetch(`./api/get_comunas.php?circuito_comunal_id=${circuitoComunalId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error HTTP! estado: ${response.status}`);
                }
                return response.json();
            })
            .then(comunas => {
                const comunaSelect = document.getElementById('comuna_id');
                comunaSelect.innerHTML = '<option value="">Selecciona una comuna (opcional)</option>';
                comunas.forEach(comuna => {
                    const option = document.createElement('option');
                    option.value = comuna.comuna_id;
                    option.textContent = comuna.nombre_comuna;
                    comunaSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error al cargar comunas:', error);
            });
    }

    function cargarConsejosComunales(comunaId) {
        return fetch(`./api/get_consejos_comunales.php?comuna_id=${comunaId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error HTTP! estado: ${response.status}`);
                }
                return response.json();
            })
            .then(consejos => {
                const consejoSelect = document.getElementById('consejo_comunal_id');
                consejoSelect.innerHTML = '<option value="">Selecciona un consejo comunal (opcional)</option>';
                consejos.forEach(consejo => {
                    const option = document.createElement('option');
                    option.value = consejo.consejo_comunal_id;
                    option.textContent = consejo.nombre_consejo_comunal;
                    consejoSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error al cargar consejos comunales:', error);
            });
    }

    function cargarCTU() {
        return fetch(`./api/get_ctu.php`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error HTTP! estado: ${response.status}`);
                }
                return response.json();
            })
            .then(ctuList => {
                const ctuSelect = document.getElementById('ctu_id');
                ctuSelect.innerHTML = '<option value="">Selecciona un CTU (opcional)</option>';
                ctuList.forEach(ctu => {
                    const option = document.createElement('option');
                    option.value = ctu.ctu_id;
                    option.textContent = ctu.nombre_ctu;
                    ctuSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error al cargar CTU:', error);
            });
    }


    function cargarCategoriasVisita() {
        fetch('./api/get_categorias_visita.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error HTTP! estado: ${response.status}`);
                }
                return response.json();
            })
            .then(categorias => {
                categorias.forEach(categoria => {
                    const option = document.createElement('option');
                    option.value = categoria.categoria_visita_id;
                    option.textContent = categoria.nombre_categoria_visita;
                    categoriaVisitaSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error al cargar categorías de visita:', error);
            });
    }

    const motivosPorCategoria = {
        "1": "Regularizacion de",
        "2": "Solicitud de informacion de",
        "3": "Entrega de",
        "4": "Cita programada",
        "5": "Solictud de empleo",
        "6": "Pasantia",
        "7": "Otro"
    };

    categoriaVisitaSelect.addEventListener('change', function() {
        const categoriaSeleccionadaId = categoriaVisitaSelect.value;
        const motivoPredeterminado = motivosPorCategoria[categoriaSeleccionadaId];
        motivoVisitaInput.value = motivoPredeterminado || "";
    });

    // Cargar datos iniciales
    cargarCategoriasVisita();
    cargarEstados();
    cargarCTU();

    // Event listeners para cambios en los selects
    estadoSelect.addEventListener('change', () => {
        cargarMunicipios(estadoSelect.value);
        cargarAlcaldias(estadoSelect.value);
    });
    municipioSelect.addEventListener('change', () => cargarParroquias(municipioSelect.value));
    parroquiaSelect.addEventListener('change', () => cargarSectores(parroquiaSelect.value));
    alcaldiaSelect.addEventListener('change', () => cargarCircuitosComunales(alcaldiaSelect.value));
    circuitoComunalSelect.addEventListener('change', () => cargarComunas(circuitoComunalSelect.value));
    comunaSelect.addEventListener('change', () => cargarConsejosComunales(comunaSelect.value));

    // Enviar formulario
    registroVisitanteForm.addEventListener('submit', function (event) {
        event.preventDefault();
    
        const formData = new FormData(registroVisitanteForm);
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });
    
        fetch('./api/registrar_visitante.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error HTTP! estado: ${response.status}`);
            }
            return response.json();
        })
        .then(responseData => {
            console.log('Respuesta del servidor:', responseData);
            if (responseData.success) {
                alert('Visitante registrado con éxito!');
                registroVisitanteForm.reset();
            } else {
                alert(`Error al registrar visitante: ${responseData.error || 'Error desconocido'}`);
            }
        })
        .catch(error => {
            console.error('Error al enviar datos de registro:', error);
            alert('Error al registrar visitante. Por favor, intenta de nuevo.');
        });
    });

    cargarEstados();
    cargarCTU();

    // Event listeners para cambios en los selects
    document.getElementById('estado_id').addEventListener('change', function () {
        const estadoId = this.value;
        if (estadoId) {
            cargarMunicipios(estadoId);
        } else {
            // Limpiar municipios, parroquias, etc. si no se selecciona un estado
            document.getElementById('municipio_id').innerHTML = '<option value="">Selecciona un municipio</option>';
            document.getElementById('parroquia_id').innerHTML = '<option value="">Selecciona una parroquia</option>';
            document.getElementById('sector_id').innerHTML = '<option value="">Selecciona un sector (opcional)</option>';
        }
    });

    document.getElementById('municipio_id').addEventListener('change', function () {
        const municipioId = this.value;
        if (municipioId) {
            cargarParroquias(municipioId);
            cargarAlcaldias(municipioId);
        } else {
            // Limpiar parroquias, sectores, etc. si no se selecciona un municipio
            document.getElementById('parroquia_id').innerHTML = '<option value="">Selecciona una parroquia</option>';
            document.getElementById('sector_id').innerHTML = '<option value="">Selecciona un sector (opcional)</option>';
        }
    });

    document.getElementById('parroquia_id').addEventListener('change', function () {
        const parroquiaId = this.value;
        if (parroquiaId) {
            cargarSectores(parroquiaId);
        } else {
            // Limpiar sectores si no se selecciona una parroquia
            document.getElementById('sector_id').innerHTML = '<option value="">Selecciona un sector (opcional)</option>';
        }
    });

    document.getElementById('alcaldia_id').addEventListener('change', function () {
        const alcaldiaId = this.value;
        if (alcaldiaId) {
            cargarCircuitosComunales(alcaldiaId);
        } else {
            // Limpiar circuitos comunales si no se selecciona una alcaldía
            document.getElementById('circuito_comunal_id').innerHTML = '<option value="">Selecciona un circuito comunal (opcional)</option>';
        }
    });

    document.getElementById('circuito_comunal_id').addEventListener('change', function () {
        const circuitoComunalId = this.value;
        if (circuitoComunalId) {
            cargarComunas(circuitoComunalId);
        } else {
            // Limpiar comunas si no se selecciona un circuito comunal
            document.getElementById('comuna_id').innerHTML = '<option value="">Selecciona una comuna (opcional)</option>';
        }
    });

    document.getElementById('comuna_id').addEventListener('change', function () {
        const comunaId = this.value;
        if (comunaId) {
            cargarConsejosComunales(comunaId);
        } else {
            // Limpiar consejos comunales si no se selecciona una comuna
            document.getElementById('consejo_comunal_id').innerHTML = '<option value="">Selecciona un consejo comunal (opcional)</option>';
        }
    });
});

    
