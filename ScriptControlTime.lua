--------------------------------------------------------------------------------
 --GLOBALS
--------------------------------------------------------------------------------
Global("wtMainPanel", nil)
Global("wtTextView", nil)  

Global("wtRText", nil)
Global("wtGText", nil) 
Global("wtYText", nil)

Global("Reactor", nil)
Global("Select", nil)

Global( "isAddon", false )

Global( "TargetID", nil )
Global( "LastTargetID", nil )
Global("RTarget", nil)

Global( "GlobalCt", 0 )
Global( "Timer", 0 )

Global( "Index", 0 )

Global( "UnitID",{})
Global( "Wid",{})

Global( "W",{})
Global( "Rd",{})
Global( "G",{})
Global( "Y",{})

Global( "GIPNOZ",{})
Global( "MP",{})

Global( "R",{})
Global( "Status",{})

	-- Статусы:
	-- 1. Моб законтролен
	-- 2. Сугроб сбит
	-- nil еще не контролили\мертв
	-- 3... 

Global( "BuffID",{})
Global( "Time",{})
Global( "Ct",{})

Global( "LastTime",{})
--------------------------------------------------------------------------------
-- Drag&Drop
--------------------------------------------------------------------------------
-- Drag&Drop by SLA, version 2010-06-19. и сохранение координат от DarkMaster

-- Drag&Drop
function DNDInit( wtReacting, wtMovable, ID )
	Global( "DragNDrop", {} )
	DragNDrop.ID = ID
	DragNDrop.wtReacting = wtReacting
	DragNDrop.wtMovable = wtMovable
	DragNDrop.IsPicked = false
	common.RegisterEventHandler( OnEventDNDPickAttempt, "EVENT_DND_PICK_ATTEMPT" )
	common.RegisterEventHandler( OnEventDNDDragTo, "EVENT_DND_DRAG_TO" )
	common.RegisterEventHandler( OnEventDNDDropAttempt, "EVENT_DND_DROP_ATTEMPT" )
	
------------- загрузка позиции
	local NewDNDWidgetInfo = wtMovable:GetPlacementPlain()
	local AddonName = common.GetAddonName()
	local SectionName = AddonName .. "_Widget"
	NewDNDWidgetInfo = userMods.GetGlobalConfigSection( SectionName ) or mainForm:GetPlacementPlain()
	mainForm:SetPlacementPlain( NewDNDWidgetInfo )
-------------	
	
	DNDReg()
end
function DNDReg()
	mission.DNDRegister( DragNDrop.wtReacting , DND_WIDGET_MOVE * DND_CONTAINER_STEP + DragNDrop.ID, true )
end
function DNDUnreg()
	mission.DNDUnregister( DragNDrop.wtReacting )
end
function OnEventDNDPickAttempt( params )
	if math.mod( params.srcId, DND_CONTAINER_STEP ) == DragNDrop.ID then
		DragNDrop.Screen = widgetsSystem:GetPosConverterParams()
		DragNDrop.Place = DragNDrop.wtMovable:GetPlacementPlain()
		DragNDrop.DeltaX = math.ceil( params.posX * DragNDrop.Screen.fullVirtualSizeX / DragNDrop.Screen.realSizeX - DragNDrop.Place.posX )
		DragNDrop.DeltaY = math.ceil( params.posY * DragNDrop.Screen.fullVirtualSizeY / DragNDrop.Screen.realSizeY - DragNDrop.Place.posY )
		common.SetCursor( "drag" )
		DragNDrop.IsPicked = true
	end
end
function OnEventDNDDragTo( params )
	if DragNDrop.IsPicked then
		DragNDrop.Place.posX = math.ceil( params.posX * DragNDrop.Screen.fullVirtualSizeX / DragNDrop.Screen.realSizeX - DragNDrop.DeltaX )
		DragNDrop.Place.posY = math.ceil( params.posY * DragNDrop.Screen.fullVirtualSizeY / DragNDrop.Screen.realSizeY - DragNDrop.DeltaY )
		DragNDrop.wtMovable:SetPlacementPlain( DragNDrop.Place )
		common.SetCursor( "drag" )
	end
end
function OnEventDNDDropAttempt( params )
	if DragNDrop.IsPicked then
		mission.DNDConfirmDropAttempt()
		common.SetCursor( "default" )
		DragNDrop.IsPicked = false
------------ сохранение позиции
		local AddonName = common.GetAddonName()
		local SectionName = AddonName .. "_Widget"
		userMods.SetGlobalConfigSection( SectionName, mainForm:GetPlacementPlain() )
------------
	end
end
--------------------------------------------------------------------------------
-- EVENT HANDLERS
--------------------------------------------------------------------------------

function OnEventBuffChange( params )
 	

 local BuffsC = unit.GetBuffCount(params.unitId)
 local ControlC = 0
 if BuffsC > 0 then
 for i = 0, BuffsC-1 do
   	local Buff = unit.GetBuff(params.unitId, i )
	if Status[params.unitId] == 1 then
		if Buff.producer.casterId == avatar.GetId() and (userMods.FromWString( Buff.name ) == "Сугроб" or userMods.FromWString( Buff.name ) ==  "Мир Призраков" or userMods.FromWString( Buff.name ) ==  "Сонное зелье" or userMods.FromWString( Buff.name ) ==  "Ментальный блок") then
 			BuffID[params.unitId] = i
			Time[params.unitId]=math.ceil(Buff.remainingMs/1000)	
            ControlC = 1
		end
	end
  end
  end
  if ControlC == 0 and Status[params.unitId] == 1  then
	Status[params.unitId]=2
	Time[params.unitId]=nil
	
	Wid[params.unitId] = Rd[params.unitId]
	G[params.unitId]:Show(false)
	W[params.unitId]:Show(false)
	Rd[params.unitId]:Show(true)
	Y[params.unitId]:Show(false)	
	MP[params.unitId] = nil
	Wid[params.unitId]:SetVal( "time", userMods.ToWString( (Ct[params.unitId]+1) .. ". Время кончилось" ))
  end
end

-- "EVENT_UNIT_BUFF_ADDED"
function OnEventUnitBuffAdded(par)
	
	if TargetID == par.unitId then
		--local TargetActiveBuffs = unit.GetBuffCount(par.unitId)
		local Buff = unit.GetBuff( par.unitId, par.index )		                                    
				--LogInfo(Buff.debugName)
							
		if (userMods.FromWString( Buff.name ) == "Сугроб" or userMods.FromWString( Buff.name ) ==  "Мир Призраков" or userMods.FromWString( Buff.name ) ==  "Сонное зелье" or userMods.FromWString( Buff.name ) ==  "Ментальный блок") and Buff.producer.casterId == avatar.GetId() and Buff.remainingMs > 1000 then
		   if Status[par.unitId] == 2 then
 			--placement.posY = placement.posY + (Ct[par.unitId] * 30 )
			Status[par.unitId] = 1
			Wid[par.unitId]:SetVal( "time", userMods.ToWString( "Подождите.." ))
			BuffID[par.unitId] = par.index
		   else
		     if Status[par.unitId] == nil then

		        if userMods.FromWString( Buff.name ) ==  "Мир Призраков"  then MP[par.unitId] = 1 end
			if userMods.FromWString( Buff.name ) ==  "Ментальный блок"  then GIPNOZ[par.unitId] = 1 end
			UnitID[par.unitId] = par.unitId			

			--local desc = wtTextView:GetWidgetDesc()           	
			--Wid[par.unitId] = mainForm:CreateWidgetByDesc( desc )
			--Wid[par.unitId]:SetName( "w" .. par.unitId )
			--mainForm:AddChild( Wid[par.unitId] )
			
			local desc = wtTextView:GetWidgetDesc()           	
			W[par.unitId] = mainForm:CreateWidgetByDesc( desc )
			W[par.unitId]:SetName( "w" .. par.unitId )
			mainForm:AddChild( W[par.unitId] )
			
			local desc = wtRText:GetWidgetDesc()           	
			Rd[par.unitId] = mainForm:CreateWidgetByDesc( desc )
			Rd[par.unitId]:SetName( "r" .. par.unitId )
			mainForm:AddChild( Rd[par.unitId] )
			
			local desc = wtGText:GetWidgetDesc()           	
			G[par.unitId] = mainForm:CreateWidgetByDesc( desc )
			G[par.unitId]:SetName( "g" .. par.unitId )
			mainForm:AddChild( G[par.unitId] )
			
			local desc = wtYText:GetWidgetDesc()           	
			Y[par.unitId] = mainForm:CreateWidgetByDesc( desc )
			Y[par.unitId]:SetName( "y" .. par.unitId )
			mainForm:AddChild( Y[par.unitId] )
			
			Wid[par.unitId] = W[par.unitId]			
			
			Wid[par.unitId]:SetVal( "time", userMods.ToWString( "Подождите.." ))
		
			local placement = wtTextView:GetPlacementPlain()
				
           		placement.posY = placement.posY + (GlobalCt * 30 ) + 30
			Ct[par.unitId] = GlobalCt			
			GlobalCt = GlobalCt + 1


			Status[par.unitId] = 1
			BuffID[par.unitId] = par.index
		
			Select:SetPlacementPlain(placement)
			Select:Show(true)
			
			desc = Reactor:GetWidgetDesc()
			R[par.unitId] = mainForm:CreateWidgetByDesc( desc )
			R[par.unitId]:SetName( "" .. par.unitId )
			mainForm:AddChild( R[par.unitId] )
			R[par.unitId]:SetPlacementPlain(placement)
			R[par.unitId]:Show(true)

			placement.posY = placement.posY + 1

			W[par.unitId]:SetPlacementPlain(placement)
			Rd[par.unitId]:SetPlacementPlain(placement)
			G[par.unitId]:SetPlacementPlain(placement)
			Y[par.unitId]:SetPlacementPlain(placement)
			
			Wid[par.unitId]:Show(true)
		     end	
			
                    end	
			if mainForm:IsVisible() == false then
				mainForm:Show(true)
				DNDReg()
				--------------------
				
				--------------------
			end			   
		 end		
	end
end

-- Targets changed
function OnEventTargetsChanged()
----
	LastTargetID = TargetID
----
	local MeID = avatar.GetId()
	TargetID = unit.GetPrimaryTarget(MeID)
----
	local flag = nil
	for i in Status do
		if i == TargetID then
			local placement = R[i]:GetPlacementPlain()
			Select:SetPlacementPlain(placement)
			flag = 1
		end 
	end
	--if flag then Select:Show(true) 
	--else Select:Show(false) end
	if not flag and RTarget ~= TargetID then  Select:Show(false) end 
end

function OnAvatarCombat(params)
	if params.inCombat == false then

		for i in Wid do
			Status[i]=nil 
			UnitID[i]=nil
			BuffID[i]=nil
			Wid[i]:DestroyWidget()
			R[i]:DestroyWidget()
			
			Rd[i]:DestroyWidget()
			W[i]:DestroyWidget()
			G[i]:DestroyWidget()
			Y[i]:DestroyWidget()
		end

		GlobalCt = 0

		for i in LastTime do LastTime[i]=nil end	
		
		mainForm:Show(false)	
		DNDUnreg()	
	
	end
		--LogInfo( "OnAvatarCombat", params.inCombat)
end


function OnEventUnitDamageReceived(params)
	if Status[params.target] == 1 then
	   if MP[params.target] == nil then
		Status[params.target] = 2
		Time[params.target]=nil
		--LogInfo("!!!")
		Wid[params.target] = Rd[params.target]
		G[params.target]:Show(false)
		W[params.target]:Show(false)
		Rd[params.target]:Show(true)
		Y[params.target]:Show(false)
		--LogInfo("!!!!")		
		Wid[params.target]:SetVal( "time", userMods.ToWString( Ct[params.target]+1 .. ". CБИЛ " .. userMods.FromWString(params.sourceName)  ))
	    end
	end
	if Status[params.target] ~= nil then
		if params.lethal then
			---------------------------
			UnitID[params.target]=nil
			BuffID[params.target]=nil
			Time[params.target]=nil
			Status[params.target]=nil
			
			Wid[params.target] = W[params.target]
			G[params.target]:Show(false)
			Rd[params.target]:Show(false)
			W[params.target]:Show(true)
			Y[params.target]:Show(false)
			
			Wid[params.target]:SetVal( "time", userMods.ToWString( Ct[params.target]+1 .. ". Убил " .. userMods.FromWString(params.sourceName)  ))	

			--R[params.target]:SetName( ""  )
			R[params.target]:Enable( false )

			---------------------------
		--	local flag = nil			
		--	for i in Status do
		--		if Status[i] == 1 then flag = 1 end 
		--	end
		--
		--	if flag == nil then
		--		mainForm:Show(false)	
		--		DNDUnreg()
		--	end
		end

	end
end


function OnReaction(params)
	if userMods.FromWString( userMods.ToWString(params.sender)) == "Reac" then
		if LastTargetID == nil then
			avatar.UnselectTarget()
			Select:Show(false)
		else
			avatar.SelectTarget( LastTargetID )
			local flag = nil
			for i in Status do
				if i == LastTargetID then
				--	local placement = R[i]:GetPlacementPlain()
				--	Select:SetPlacementPlain(placement)
					flag = 1
				end 
			end
			if not flag then
				local placement = Reactor:GetPlacementPlain()
				Select:SetPlacementPlain(placement)
				RTarget = LastTargetID				
			end
			Select:Show(true)
		end
	else
		local id = common.GetIntFromWString( userMods.ToWString( params.sender ))
		if id == nil then avatar.UnselectTarget()
		else 
			avatar.SelectTarget( id )
			Select:Show(true)
		end
	end
end
-- Плохое решение проблемы, связанной с останавливающимися таймерами.. хорошего еще не могу придумать
function OnTimer( params )
	if mainForm:IsVisible() == true then
		Timer = Timer + 1
		--if Timer == 3 then
			Timer = 0
			for i in Time do 
				if Timer == 3 then
					if Time[i] == LastTime[i] then
						UnitID[i]=nil
						BuffID[i]=nil
						Time[i]=nil
						Status[i]=nil
						Wid[i]:SetVal( "time", userMods.ToWString( Ct[i]+1 .. ". Таймер остановился :'( " ))

					else
						LastTime[i] = Time[i]
					end
				end
--------------------------------------------------
				local Buff = unit.GetBuff(UnitID[i], BuffID[i] )
	
				if Buff then
				Time[i]=math.ceil(Buff.remainingMs/1000)-1
				
				
				if Time[i] > 16 then 
					Wid[i] = G[i]
					W[i]:Show(false)
					Rd[i]:Show(false)
					G[i]:Show(true)
					Y[i]:Show(false)
				else 
					if Time[i] > 6 then
						Wid[i] = Y[i]
						W[i]:Show(false)
						Rd[i]:Show(false)
						Y[i]:Show(true)
						G[i]:Show(false)
					else
						Wid[i] = Rd[i]
						W[i]:Show(false)
						G[i]:Show(false)
						Rd[i]:Show(true)
						Y[i]:Show(false)
					end
				end	
				
 				if userMods.FromWString( Buff.name ) == "Сугроб" then Wid[i]:SetVal( "time", userMods.ToWString( (Ct[i]+1) .. ". Сугроб: " .. Time[i] .. " сек.")) end
				if userMods.FromWString( Buff.name ) == "Мир Призраков" then Wid[i]:SetVal( "time", userMods.ToWString( (Ct[i]+1) .. ". Мир: " .. Time[i] .. " сек.")) end
 				if userMods.FromWString( Buff.name ) == "Сонное зелье" then Wid[i]:SetVal( "time", userMods.ToWString( (Ct[i]+1) .. ". Сон: " .. Time[i] .. " сек.")) end
 				if userMods.FromWString( Buff.name ) == "Ментальный блок" then
 			
 			 		local BuffsC = unit.GetBuffCount(params.unitId)
 			 		 if BuffsC > 0 then
  					for i = 0, BuffsC-1 do
   						Buff = unit.GetBuff(params.unitId, i )
						if Buff.producer.casterId == avatar.GetId() and userMods.FromWString( Buff.name ) == "Цель контакта" then
 							Wid[i]:SetVal( "time", userMods.ToWString( (Ct[i]+1) .. ". Гипноз: " .. Time[i] .. "|" .. math.ceil(Buff.remainingMs/1000)-1 .. " сек."))
 						end
 						if Buff.producer.casterId == avatar.GetId() and userMods.FromWString( Buff.name ) == "Подавление воли" then
 							Wid[i]:SetVal( "time", userMods.ToWString( (Ct[i]+1) .. ". Гипноз: " .. Time[i] .. "|" .. math.ceil(Buff.remainingMs/1000)-1 .. " сек."))
 						end						
 					end
 					end
 					
 				end

				Wid[i]:Show(true)
				
				
				if Time[i] <= 0 then
					Status[i]=2
					Time[i]=nil
					Wid[i]:SetVal( "time", userMods.ToWString( (Ct[i]+1) .. ". Время кончилось" ))
					MP[i] = nil
					Wid[i] = W[i]
					G[i]:Show(false)
					Rd[i]:Show(false)
					W[i]:Show(true)
					Y[i]:Show(false)
				end
			       else
					Status[i]=2
					Time[i]=nil
					Wid[i]:SetVal( "time", userMods.ToWString( (Ct[i]+1) .. ". Время кончилось" ))
					MP[i] = nil
					Wid[i] = W[i]
					G[i]:Show(false)
					Rd[i]:Show(false)
					W[i]:Show(true)
					Y[i]:Show(false)	         
			       end
----------------------------------------------------
			end	
		--end
	end
end
--------------------------------------------------------------------------------
function DeliteW(params)
	Status[params]=2
	UnitID[params]=nil
	BuffID[params]=nil
	Time[params]=nil
	Wid[params]:DestroyWidget() 	
	R[params]:DestroyWidget() 
	Rd[params]:DestroyWidget()
	W[params]:DestroyWidget()
	G[params]:DestroyWidget()
	Y[params]:DestroyWidget()
end
--------------------------------------------------------------------------------
-- INITIALIZATION
--------------------------------------------------------------------------------
function Init()  
	  
	common.RegisterEventHandler( OnEventTargetsChanged, "EVENT_AVATAR_PRIMARY_TARGET_CHANGED" )
	common.RegisterEventHandler( OnEventBuffChange, "EVENT_UNIT_BUFFS_CHANGED")              ---EVENT_UNIT_BUFFS_ELEMENT_CHANGED" ) 
	common.RegisterEventHandler( OnEventUnitBuffAdded, "EVENT_UNIT_BUFF_ADDED" ) 
	common.RegisterEventHandler( OnEventUnitDamageReceived, "EVENT_UNIT_DAMAGE_RECEIVED" )
	common.RegisterEventHandler( OnAvatarCombat, "EVENT_AVATAR_COMBAT_STATUS_CHANGED" )
	common.RegisterEventHandler( OnTimer, "EVENT_SECOND_TIMER" )

	Reactor = mainForm:GetChildChecked( "Reac", false )

	wtTextView = mainForm:GetChildChecked( "Time", false )
	wtRText = mainForm:GetChildChecked( "RTime", false )
	wtGText = mainForm:GetChildChecked( "GTime", false )
	wtYText = mainForm:GetChildChecked( "YTime", false )
	Select = mainForm:GetChildChecked( "Select", false )
------------------
	wtTextView:SetVal( "time", userMods.ToWString( "Прошлая цель" ))
	local placement = wtTextView:GetPlacementPlain()
	Reactor:SetPlacementPlain(placement)
------------------
	Select:Show(false)
	wtRText:Show(false)
	wtGText:Show(false)
	wtYText:Show(false)
		
	common.RegisterReactionHandler( OnReaction, "ReactionClick" )

  	DNDInit( Reactor, mainForm, 567 )
	mainForm:Show(false)	
	DNDUnreg()
end
--------------------------------------------------------------------------------
Init()
--------------------------------------------------------------------------------
