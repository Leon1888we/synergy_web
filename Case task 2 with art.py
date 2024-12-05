import datetime
from datetime import date
from art import *

today = date.today()
day = int(input("Введите число рождения: "))
month = int(input("Введите месяц рождения: "))
year = int(input("Введите год рождения: "))
date = datetime.datetime(year, month, day)
print("День вашего рождения (0-понедельник, 1-вторник, 2-среда, 3-четверг, 4-пятница, 5-суббота, 6-воскресенье): ", date.weekday())
if (year % 4 == 0 and year % 100 != 0) or year % 400 == 0:
    print('Год високосный.')
else:
    print('Год не високосный.')
age = today.year - year - ((today.month, today.day) < (month, day))  
print(f'Ваш возраст: {age}')
def display_date_as_stars(day, month, year):
    day_str = f"{day:02d}"
    month_str = f"{month:02d}"
    year_str = f"{year:04d}"
    date_str = f"{day_str} {month_str} {year_str}"
    print(text2art(date_str))
print(display_date_as_stars(day, month, year))

