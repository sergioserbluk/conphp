"""
Vehicle Data Exercise

This exercise helps you practice Python data structures by modeling
brand and model information for vehicles.

Instructions:
1. Use a dictionary to store brand names as keys and lists of models as values.
2. Implement functions to:
   - add_brand(brand_name)
   - add_model(brand_name, model_name)
   - list_brands()
   - list_models(brand_name)
3. Optionally, store the data in a JSON file for persistence.
4. Provide a simple CLI menu to test the functionality.
"""

import json
from pathlib import Path

data_file = Path("vehicle_data.json")


def load_data():
    if data_file.exists():
        with data_file.open("r", encoding="utf-8") as f:
            return json.load(f)
    return {}


def save_data(data):
    with data_file.open("w", encoding="utf-8") as f:
        json.dump(data, f, indent=2)


def add_brand(data, brand_name):
    data.setdefault(brand_name, [])


def add_model(data, brand_name, model_name):
    models = data.setdefault(brand_name, [])
    if model_name not in models:
        models.append(model_name)


def list_brands(data):
    return list(data.keys())


def list_models(data, brand_name):
    return data.get(brand_name, [])


if __name__ == "__main__":
    data = load_data()
    while True:
        print("\nVehicle Data Menu")
        print("1. Add brand")
        print("2. Add model")
        print("3. List brands")
        print("4. List models for a brand")
        print("5. Save and exit")
        choice = input("Select an option: ")

        if choice == "1":
            brand = input("Brand name: ")
            add_brand(data, brand)
        elif choice == "2":
            brand = input("Brand name: ")
            model = input("Model name: ")
            add_model(data, brand, model)
        elif choice == "3":
            for brand in list_brands(data):
                print(brand)
        elif choice == "4":
            brand = input("Brand name: ")
            for model in list_models(data, brand):
                print(model)
        elif choice == "5":
            save_data(data)
            break
        else:
            print("Invalid option")
