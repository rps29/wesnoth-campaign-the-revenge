# Macro documentation for Eternal Silence

***This documentation is automatically generated. Manual edits to this file will be overridden!***

## From file abilities.cfg

**WEAPON_SPECIAL_KNOCKOUT**
No documentation found.

**WEAPON_SPECIAL_FREEZE**
No documentation found.

**EVENT_FREEZE**
No documentation found.

**ABILITY_RESURRECT**
No documentation found.

**EVENT_RESURRECT**
No documentation found.

**RESURRECT_SELECTION_UNIT** *TYPE COST LABEL IMAGE*
No documentation found.

**MENU_ITEM_RESURRECT**
No documentation found.

**ABILITY_DEVOUR**
No documentation found.

**DEVOUR_SELECTION_UNIT** *INDEX*
No documentation found.

**MENU_ITEM_DEVOUR**
No documentation found.

## From file bigmap.cfg

**BIGMAP**

- This macro is copied from Son Of The Black Eye - utils/bigmap.cfg


**JOURNEY** *X Y*

- This macro is copied from Son Of The Black Eye - utils/bigmap.cfg


## From file characters.cfg

**UNIT_TEDDER** *X Y*
No documentation found.

**UNIT_UGOKI** *X Y*
No documentation found.

**UNIT_NEKROMANT** *X Y*
No documentation found.

**UNIT_GRÄFRAT** *X Y*
No documentation found.

**UNIT_KIKI** *X Y*
No documentation found.

**UNIT_DRALGUR** *X Y*
No documentation found.

**UNIT_KRONK** *X Y*
No documentation found.

**UNIT_URUKNORG** *X Y*
No documentation found.

**UNIT_DISGUSTUS** *X Y*
No documentation found.

**UNIT_VASOLIN** *X Y*
No documentation found.

**UNIT_STARCHER** *X Y*
No documentation found.

**UNIT_JAILER** *X Y*
No documentation found.

**UNIT_WICKBERT** *X Y*
No documentation found.

**UNIT_RUDOLT** *X Y*
No documentation found.

**UNIT_LANDOLIN** *X Y*
No documentation found.

## From file descriptions.cfg

**UNIT_DESCRIPTION_TEDDER**
No documentation found.

**UNIT_DESCRIPTION_UNDEAD_TROLL**
No documentation found.

**UNIT_DESCRIPTION_INSANE_NECROMANCER**
No documentation found.

**UNIT_DESCRIPTION_NAUGHTY_GIRL**
No documentation found.

**UNIT_DESCRIPTION_EXILED_DWARF**
No documentation found.

**UNIT_DESCRIPTION_SKELETON_SNIPER**
No documentation found.

**UNIT_DESCRIPTION_SKELETON_DEATH_SHOOTER**
No documentation found.

**UNIT_DESCRIPTION_SKELETON_FAULTY_SUMMONED**
No documentation found.

**UNIT_DESCRIPTION_BONE_RIDER**
No documentation found.

**UNIT_DESCRIPTION_TROLL_WARRIOR**
No documentation found.

**UNIT_DESCRIPTION_THUG**
No documentation found.

**UNIT_DESCRIPTION_BANDIT**
No documentation found.

**UNIT_DESCRIPTION_HIGHWAYMAN**
No documentation found.

**UNIT_DESCRIPTION_FOOTPAD**
No documentation found.

**UNIT_DESCRIPTION_OUTLAW**
No documentation found.

**UNIT_DESCRIPTION_FUGITIVE**
No documentation found.

**WEAPON_SPECIAL_DESCRIPTION_KNOCKOUT**
No documentation found.

**SPECIAL_NOTES_KNOCKOUT**
No documentation found.

**WEAPON_SPECIAL_DESCRIPTION_FREEZE**
No documentation found.

**SPECIAL_NOTES_FREEZE**
No documentation found.

**ABILITY_DESCRIPTION_RESURRECT**
No documentation found.

**MENU_ITEM_DESCRIPTION_RESURRECT**
No documentation found.

**ABILITY_DESCRIPTION_DEVOUR**
No documentation found.

**MENU_ITEM_DESCRIPTION_DEVOUR**
No documentation found.

## From file macros.cfg

**UNIT_GUARDIAN** *SIDE TYPE X Y*

- Generate unit with guardian AI-special
- SIDE: The guardian unit will be created at this side
- X and Y: The gaurdian unit will be created at these coordinates


**UNIT_TRANSFORM** *ID NEW_TYPE*

- Providing a [have_unit] check in order to transform a unit.
- Used for main characters (Tedder, Ugoki) at Scenario 1 -> 2 and scenario 2 -> 3
- ID: The id of the unit to be transformed
- NEW_TYPE: The type the unit will be transformed to


**SPEAK** *SPEAKER MESSAGE*

- Shortcut for message
- SPEAKER: The ID of the speaker
- MESSAGE: String, message the speaker {SPEAKER} will speak


**SPEAK_NARRATOR** *MESSAGE*

- Shortcut for 'narrator'-messages
- MESSAGE: Check {SPEAK} macro


**SPEAK_ALLY** *MSG_NEKROMANT MSG_URUKNORG*
No documentation found.

**VICTORY**

- Call this macro when the scenario is won


**DEFEAT**

- Call this macro when the scenario is lost


**OBJECTIVE** *CONDITION DESCRIPTION*

- Adds an [objective] to your [objectives] section
- CONDITION: The condition - lose or win
- DESCRIPTION: The description of the objective


**OBJ_NOTE** *DESCRIPTION*

- Adds a [note] to your [objectives] section
- DESCRIPTION: The description of the note


**CREATE_CASTLE** *X Y*

- This macro creates an usual castle around given X and Y coordinates. Terrains used are Ce surrounding an Ke castle keep for the X and Y coordinates
- X and Y: The coordinates for the keep


## From file missile-frames.cfg

**MISSILE_FRAME_SOUL_ERADICATION**

- Missile frame animation for the "soul eradication"
- By now used by the story characters Tedder and Ugoki only, as soon as they advance to Undead Trolls


**MISSILE_FRAME_CHASTISING**

- Missile frame animation for the "chastising"
- Created for Nekromant only


**MISSILE_FRAME_MADMAN_SLING_HIT** *OFFSET_X OFFSET_Y*
No documentation found.

**MISSILE_FRAME_MADMAN_SLING_MISS** *OFFSET_X OFFSET_Y*
No documentation found.

## From file sides.cfg

**SIDE_TEDDER_BEGINNING** *X Y*

- Side specifications for scenario 1
- This defines side specific attributes for the first scenario of the campaign Eternal Silence
- X: The X coordinates Tedder will be created at
- Y: The Y coordinates Tedder will be created at


**SIDE_TEDDER_RESURRECTION** *X Y*

- Side specifications for scenario 2
- Read SIDE_TEDDER_BEGINNING macro


**SIDE_TEDDER_UNDEAD** *X Y*

- Side specifications for scenario 3, 4, 5, 6 and 7
- Read SIDE_TEDDER_BEGINNING macro


**INIT_CHOSEN_ALLY**

- Check whether a chosen_ally has been saved before or not. If it hasn't, create Nekromant as your ally by default
- If ally hasn't been created before, also set the stored_ally_leader and stored_ally_side
- Same checks and initializations for the stored_chosen_enemy


## From file special-hail.cfg

**WEAPON_SPECIAL_HAIL**
No documentation found.

**EVENT_HAIL**
No documentation found.

**SPECIAL_NOTES_HAIL**
No documentation found.

## From file terrain.cfg

## From file terrain_graphics.cfg

