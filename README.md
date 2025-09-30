# Pungeons Hnd Pragons

## About  
*Pungeons Hnd Pragons* is a browser-based online multiplayer fighting game inspired by Dungeons & Dragons dice mechanics. Developed with PHP, MySQL, and AJAX, the game includes secure login, matchmaking, turn-based combat, ELO rating, chat, and anti-cheat logic.

## Quickfacts  
- **Platform:** Browser (Chrome / Firefox)  
- **Language:** German  

## Controls / UI  
- Mouse-driven interface for attack selection, lobby navigation, chat, etc.  
- Button / UI elements for game actions (no direct keyboard commands).  

## Known Issues   
- No real-time audio or sound feedback implemented.  
- UI occasionally lacks responsive behavior on very small or very large screens.  
- Anti-cheat is basic; may not cover all types of malicious requests.  
- Some hero stat imbalances remain (certain heroes dominate in matches).  

## Possible Improvements  
- Add sound effects or music to enrich gameplay feedback.  
- Improve UI responsiveness and layout for varying screen sizes.  
- Harden anti-cheat logic (e.g. validate more endpoints on server).  
- Add more combat mechanics (skills, status effects).  
- Better balancing & tuning of hero attributes.  
- Add animations / visual effects for attacks and transitions.

## Installation / Setup  
1. Clone or download this repository  
2. Import the `database.sql` schema into your MySQL/MariaDB database  
3. Update the config.inc file with your database credentials  
4. Deploy the PHP backend to a server (Apache / Nginx / etc.)  
5. Open the frontend in your browser and login / register  

## Author  
Created by [Andreas Knabel](https://www.andreasknabel.at)
