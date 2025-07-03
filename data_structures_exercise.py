"""Simple Data Structures Exercise

Este ejercicio está pensado para practicar listas y diccionarios en Python en unas dos horas.

Implementa un pequeño registro de alumnos utilizando una lista de diccionarios.
Cada diccionario representa a un alumno con las claves 'dni', 'nombre' y 'carrera'.

Funciones sugeridas:
    - add_student(registry, dni, nombre, carrera)
    - list_students(registry)
    - find_student(registry, dni)
Proporciona un pequeño menú CLI para interactuar con el registro.
"""

from typing import List, Dict, Optional

Student = Dict[str, str]


def add_student(registry: List[Student], dni: str, nombre: str, carrera: str) -> None:
    if find_student(registry, dni) is None:
        registry.append({"dni": dni, "nombre": nombre, "carrera": carrera})


def list_students(registry: List[Student]) -> List[Student]:
    return registry


def find_student(registry: List[Student], dni: str) -> Optional[Student]:
    for student in registry:
        if student["dni"] == dni:
            return student
    return None


if __name__ == "__main__":
    registry: List[Student] = []
    while True:
        print("\nMenu de Alumnos")
        print("1. Agregar alumno")
        print("2. Listar alumnos")
        print("3. Buscar alumno")
        print("4. Salir")
        choice = input("Seleccione una opcion: ")

        if choice == "1":
            dni = input("DNI: ")
            nombre = input("Nombre: ")
            carrera = input("Carrera: ")
            add_student(registry, dni, nombre, carrera)
        elif choice == "2":
            for s in list_students(registry):
                print(f"{s['dni']} - {s['nombre']} - {s['carrera']}")
        elif choice == "3":
            dni = input("DNI: ")
            estudiante = find_student(registry, dni)
            if estudiante:
                print(estudiante)
            else:
                print("Alumno no encontrado")
        elif choice == "4":
            break
        else:
            print("Opcion invalida")
