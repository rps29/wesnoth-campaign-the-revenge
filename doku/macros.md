# Macro documentation for The Revenge

***This documentation is automatically generated. Manual edits to this file will be overridden!***

## From file attacks.cfg

**ATTACK_SOUL_ERADICATION** *DAMAGE NUMBER*

- [attack] for the "soul eradication"
- DAMAGE: The damage for [attack]
- NUMBER: The number for [attack]


## From file macros.cfg

**IMG** *PATH*

- Include an image located at data/add-ons/The_Revenge/images/
- PATH: The path trailing after images/*


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


## From file sides.cfg

**SIDE_TEDDER_BEGINNING** *X Y*

- Side specifications for scenario 1
- This defines side specific attributes for the first scenario of the campaign The Revenge
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


## From file units.cfg

**TEDDER**

- Defines unit attributes for Tedder that never change in this campaign


**UGOKI**

- Defines unit attributes for Ugoki that never change in this campaign


**UNIT_NEKROMANT** *SIDE X Y*

- Creates the complete Nekromant unit with Side, X and Y parameters
- Side: Nekromant will be created at this side - If you pass a different side than the previous side Nekromant has been played at before, it will create a new unit. In this case, use the old side and follow up with a {MODIFY_UNIT id=nekromant side <new side>}
- X: The X coordinate Nekromant will be created at
- Y: The Y coordinate Nekromant will be created at


**UNIT_URUKNORG** *SIDE X Y*

- Creates Uruknorg, the Orc leader as unit
- Parameters same as for UNIT_NEKROMANT macro


