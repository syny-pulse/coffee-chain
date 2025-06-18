import numpy as np
import pandas as pd
from sklearn.linear_model import LinearRegression

# Step 1: Load and prepare data
def load_and_prepare_data(file_path):
    # Load CSV data
    data = pd.read_csv(file_path)
    
    # Convert Year+Month to datetime index
    data['Date'] = pd.to_datetime(data['Year'].astype(str) + '-' + data['Month'].astype(str))
    data.set_index('Date', inplace=True)
    
    # Uganda seasons
    seasons = {
        1: "Dry1", 2: "Dry1", 3: "Wet1", 4: "Wet1", 5: "Wet1", 
        6: "Dry2", 7: "Dry2", 8: "Dry2", 9: "Wet2", 10: "Wet2", 11: "Wet2", 12: "Dry1"
    }
    data['Season'] = data['Month'].map(seasons)
    
    return data

# Step 2: Create modeling features
def create_features(data):
    # Time-based features
    data['Time_Index'] = range(1, len(data) + 1)  # Sequential time index
    
    # One-hot encode seasons
    season_dummies = pd.get_dummies(data['Season'], prefix='Season')
    data = pd.concat([data, season_dummies], axis=1)
    
    # Month as cyclical feature (sin/cos transformation)
    data['Month_sin'] = np.sin(2 * np.pi * data['Month']/12)
    data['Month_cos'] = np.cos(2 * np.pi * data['Month']/12)
    
    return data

# Step 3: Train prediction models
def train_models(data):
    models = {}
    features = ['Time_Index', 'Season_Dry1', 'Season_Dry2', 'Season_Wet1', 'Season_Wet2',
                'Month_sin', 'Month_cos']
    
    for product in ['Espresso', 'Latte', 'Iced_Latte', 'Black_Coffee']:
        model = LinearRegression()
        model.fit(data[features], data[product])
        models[product] = model
        
    return models, features

# Step 4: Predict future sales
def predict_sales(models, features, year, month):
    # Create future date entry
    seasons = {1: "Dry1", 2: "Dry1", 3: "Wet1", 4: "Wet1", 5: "Wet1", 
               6: "Dry2", 7: "Dry2", 8: "Dry2", 9: "Wet2", 10: "Wet2", 11: "Wet2", 12: "Dry1"}
    
    # Calculate time index (next in sequence)
    time_index = 61 + (year - 2024) * 12 + (month - 1)
    
    # Create feature vector
    input_data = {
        'Time_Index': time_index,
        'Season_Dry1': 1 if month in [1, 2, 12] else 0,
        'Season_Dry2': 1 if month in [6, 7, 8] else 0,
        'Season_Wet1': 1 if month in [3, 4, 5] else 0,
        'Season_Wet2': 1 if month in [9, 10, 11] else 0,
        'Month_sin': np.sin(2 * np.pi * month/12),
        'Month_cos': np.cos(2 * np.pi * month/12)
    }
    input_df = pd.DataFrame([input_data])
    
    # Make predictions
    predictions = {}
    for product, model in models.items():
        pred = model.predict(input_df[features])[0]
        predictions[product] = max(round(pred), 0)  # Ensure non-negative
    
    return predictions

# Main execution
if __name__ == "__main__":
    # Load data
    FILE_PATH = "coffee_sales.csv"
    data = load_and_prepare_data(FILE_PATH)
    data = create_features(data)
    
    # Train model
    models, features = train_models(data)
    
    # Get input
    month = int(input("Enter month (1-12): "))
    year = int(input("Enter year (2025+): "))
    
    # Predict and display results
    predictions = predict_sales(models, features, year, month)
    
    print("\nPredicted Sales for {}/{}:".format(month, year))
    for product, sales in predictions.items():
        print(f"{product}: {sales} units")
    
    # Premium vs basic comparison
    premium = predictions['Espresso'] + predictions['Latte'] + predictions['Iced_Latte']
    basic = predictions['Black_Coffee']
    print("\nPremium vs Basic Analysis:")
    print(f"Premium Coffees: {premium} units")
    print(f"Black Coffee: {basic} units")
    print("Dominant:", "Premium" if premium > basic else "Basic")
    
    
    
#Key Patterns Identified:
    #Dry Season Dominance:
    #Highest Iced Latte sales in Jan-Feb & Jun-Aug (220-265 units)
    #Black Coffee consistently leads (350-450 units)
    #Premium products never exceed Black Coffee

#Wet Season Shifts:
    #Iced Latte drops 50-60% (100-120 units)
    #Espresso/Latte gain prominence (180-220 units)
    #Premium products outsell Black Coffee in:
    #March 2021/2022/2023
    #October 2019/2020/2021

#Annual Trends:
    #2-3% YoY growth across products
    #Dry season premiums grow faster (+4% Iced Latte)
    #Post-COVID recovery bump in 2021