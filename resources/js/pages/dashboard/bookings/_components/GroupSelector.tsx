import { Button } from '@/components/ui/button';
import { AirportGroup } from '../_types/booking';
import { cn } from '@/lib/utils';
import { Building2, TowerControl } from 'lucide-react';

interface GroupSelectorProps {
    groups: AirportGroup[];
    selectedGroupId: string;
    onSelectGroup: (groupId: string) => void;
}

export function GroupSelector({
    groups,
    selectedGroupId,
    onSelectGroup,
}: GroupSelectorProps) {
    return (
        <div className="flex flex-wrap gap-2">
            {groups.map((group) => (
                <Button
                    key={group.id}
                    onClick={() => onSelectGroup(group.id)}
                    variant={'outline'}
                    className={cn(
                        'flex items-center gap-2 px-4 py-2 rounded-lg border transition-all duration-200',
                        'text-sm font-medium',
                        selectedGroupId === group.id
                            ? 'bg-primary text-primary-foreground border-primary hover:bg-primary hover:text-primary-foreground'
                            : 'bg-card border-border text-muted-foreground hover:text-foreground hover:border-primary/50',
                    )}
                >
                    <TowerControl className="w-4 h-4" />
                    <span>{group.icao}</span>
                </Button>
            ))}
        </div>
    );
}
