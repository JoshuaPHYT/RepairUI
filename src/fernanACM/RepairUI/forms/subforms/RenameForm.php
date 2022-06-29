<?php

#  ____                           _          _   _   ___ 
# |  _ \    ___   _ __     __ _  (_)  _ __  | | | | |_ _|
# | |_) |  / _ \ | '_ \   / _` | | | | '__| | | | |  | | 
# |  _ <  |  __/ | |_) | | (_| | | | | |    | |_| |  | | 
# |_| \_\  \___| | .__/   \__,_| |_| |_|     \___/  |___|
#                |_|                                     
#   Copyright [2022-2022] [fernanACM]

#   Licensed under the Apache License, Version 2.0 (the "License");
#   you may not use this file except in compliance with the License.
#   You may obtain a copy of the License at

#       http://www.apache.org/licenses/LICENSE-2.0

#   Unless required by applicable law or agreed to in writing, software
#   distributed under the License is distributed on an "AS IS" BASIS,
#   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
#   See the License for the specific language governing permissions and
#   limitations under the License. 

namespace fernanACM\RepairUI\forms\subforms;

use pocketmine\Server;
use pocketmine\player\Player;

use Vecnavium\FormsUI\CustomForm;

use davidglitch04\libEco\libEco;

use pocketmine\item\ToolTier;
use pocketmine\item\Armor;
use pocketmine\item\Item;
use pocketmine\item\Tool;

use fernanACM\RepairUI\RP;
use fernanACM\RepairUI\utils\PluginUtils;

class RenameForm{

	public function RenameMoney(Player $player){
		libEco::myMoney($player, static function(float $myMoney) use($player): void{
			$form = new CustomForm(function(Player $player, $data = null){
                if(!empty($data[1])){
                    $prefix = RP::getInstance()->getMessage($player, "Prefix");
				    $amount = RP::getInstance()->config->getNested("RepairCost.Rename.Money");
                    libEco::reduceMoney($player, $amount, static function(bool $success) use($player, $amount, $data): void{
                        if($success){
                            $item = $player->getInventory()->getItemInHand();
                            $item->setCustomName(str_replace(["&", "{LINE}"], ["§", "\n"], $data[1]));
                            $player->getInventory()->setItemInHand($item);
                            $message = RP::getInstance()->getMessage($player, "Messages.rename-success");
                            $prefix = RP::getInstance()->getMessage($player, "Prefix");
                            $player->sendMessage($prefix . str_replace(["{RENAME}"], [$data[1]], $message));
                            PluginUtils::PlaySound($player, "random.anvil_use", 1, 1);
                        }else{
                            $prefix = RP::getInstance()->getMessage($player, "Prefix");
                            $player->sendMessage($prefix . RP::getInstance()->getMessage($player, "Messages.no-money"));
						    PluginUtils::PlaySound($player, "mob.villager.no", 1, 1);
                        }
                    });
                }else{
                    $prefix = RP::getInstance()->getMessage($player, "Prefix");
				    $player->sendMessage($prefix . RP::getInstance()->getMessage($player, "Messages.rename-null"));
                    PluginUtils::PlaySound($player, "mob.villager.no", 1, 1);
				    return;
                }
			});
			$prefix = RP::getInstance()->getMessage($player, "Prefix");
			$amount = RP::getInstance()->config->getNested("RepairCost.Rename.Money");
			$message = RP::getInstance()->getMessage($player, "Forms.RenameMoney.content");
			$item = $player->getInventory()->getItemInHand();
            if($item->getId() == 0){
                $player->sendMessage($prefix . RP::getInstance()->getMessage($player, "Messages.no-item"));
                PluginUtils::PlaySound($player, "mob.villager.no", 1, 1);
                return;
            }
            $form->setTitle(RP::getInstance()->getMessage($player, "Forms.RenameMoney.title"));
            $form->addLabel(str_replace(["{BALANCE}", "{COST}"], [$myMoney, $amount], $message));
            $form->addInput(RP::getInstance()->getMessage($player, "Forms.RenameMoney.input1"), RP::getInstance()->getMessage($player, "Forms.RenameMoney.input2"));
            $player->sendForm($form);
		});
	}

	public function RenameXP(Player $player){
		$form = new CustomForm(function(Player $player, $data = null){
			if(!empty($data[1])){
                $prefix = RP::getInstance()->getMessage($player, "Prefix");
			    $amount = RP::getInstance()->config->getNested("RepairCost.Rename.XP");
                if($player->getXpManager()->getXpLevel() >= $amount){
                    $item = $player->getInventory()->getItemInHand();
                    $player->getXpManager()->subtractXpLevels($amount);
				    $item->setCustomName(str_replace(["&", "{LINE}"], ["§", "\n"], $data[1]));
                    $player->getInventory()->setItemInHand($item);
                    $message = RP::getInstance()->getMessage($player, "Messages.rename-success");
                    $prefix = RP::getInstance()->getMessage($player, "Prefix");
                    $player->sendMessage($prefix . str_replace(["{RENAME}"], [$data[1]], $message));
                    PluginUtils::PlaySound($player, "random.anvil_use", 1, 1);
                }else{
                    $prefix = RP::getInstance()->getMessage($player, "Prefix");
                    $player->sendMessage($prefix . RP::getInstance()->getMessage($player, "Messages.no-xp"));
				    PluginUtils::PlaySound($player, "mob.villager.no", 1, 1);
                }
            }else{
                $prefix = RP::getInstance()->getMessage($player, "Prefix");
				$player->sendMessage($prefix . RP::getInstance()->getMessage($player, "Messages.rename-null"));
                PluginUtils::PlaySound($player, "mob.villager.no", 1, 1);
				return;
            }
		});
		$prefix = RP::getInstance()->getMessage($player, "Prefix");
		$amount = RP::getInstance()->config->getNested("RepairCost.Rename.XP");
		$message = RP::getInstance()->getMessage($player, "Forms.RenameXP.content");
		$item = $player->getInventory()->getItemInHand();
        if($item->getId() == 0){
            $player->sendMessage($prefix . RP::getInstance()->getMessage($player, "Messages.no-item"));
            PluginUtils::PlaySound($player, "mob.villager.no", 1, 1);
            return;
        }
        $form->setTitle(RP::getInstance()->getMessage($player, "Forms.RenameXP.title"));
        $form->addLabel(str_replace(["{XP}", "{COST}"], [$player->getXpManager()->getXpLevel(), $amount], $message));
        $form->addInput(RP::getInstance()->getMessage($player, "Forms.RenameXP.input1"), RP::getInstance()->getMessage($player, "Forms.RenameXP.input2"));
        $player->sendForm($form);
	}
}