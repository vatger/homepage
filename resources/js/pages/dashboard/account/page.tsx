import {
    BellDot,
    InfoIcon,
    KeyRound,
    Mail,
    MapPin,
    ShieldCheck,
    Trash2,
    UserRound,
} from 'lucide-react';
import Link from 'next/link';

import { Alert, AlertDescription } from '@/components/ui/alert';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import { Spinner } from '@/components/ui/spinner';
import { Switch } from '@/components/ui/switch';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Textarea } from '@/components/ui/textarea';

type User = {
    name: string;
    email: string;
    role: string;
    memberSince: string;
    fir: string;
    atcRating: string;
    vatsimId: string;
    location: string;
    avatarUrl: string;
    bio: string;
};

const user: User = {
    name: 'Max Mustermann',
    email: 'max.mustermann@vatsim.net',
    role: 'Administrator',
    memberSince: 'Jan 2024',
    fir: 'EDWW',
    atcRating: 'C1',
    vatsimId: '1234567',
    location: 'Germany',
    avatarUrl: 'https://ui-avatars.com/api/?name=Max+Mustermann',
    bio: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.',
};

export default function ProfilePage() {
    const initials = user.name
        .split(' ')
        .slice(0, 2)
        .map((p) => p[0]?.toUpperCase())
        .join('');

    return (
        <>
            <h1 className="text-3xl font-bold mb-5">Account</h1>

            <div className="grid grid-cols-12 gap-4">
                <div className="col-span-12 lg:col-span-2">
                    <Card className="overflow-hidden">
                        <CardHeader className="flex flex-col items-center gap-3">
                            <Avatar className="h-16 w-16">
                                <AvatarImage
                                    src={user.avatarUrl}
                                    alt={user.name}
                                />
                                <AvatarFallback>{initials}</AvatarFallback>
                            </Avatar>

                            <div className="text-center">
                                <h2 className="text-lg font-bold leading-tight">
                                    {user.name}
                                </h2>
                                <p className="text-sm text-muted-foreground">
                                    {user.vatsimId}
                                </p>
                            </div>

                            <div className="flex flex-wrap items-center justify-center gap-2 mt-2">
                                <Badge variant="outline">
                                    {user.atcRating}
                                </Badge>
                                <Badge variant="outline">P2</Badge>
                                <Badge variant="outline">FIR {user.fir}</Badge>
                            </div>

                            <Separator className="my-2" />

                            <div className="w-full space-y-2 text-sm">
                                <div className="flex items-center justify-between">
                                    <p className="text-muted-foreground">
                                        Member since
                                    </p>
                                    <p className="font-medium">
                                        {user.memberSince}
                                    </p>
                                </div>

                                <div className="flex items-center justify-between">
                                    <p className="text-muted-foreground">
                                        Region
                                    </p>
                                    <p className="font-medium">VATSIM EMEA</p>
                                </div>

                                <div className="flex items-center justify-between">
                                    <p className="text-muted-foreground">
                                        Division
                                    </p>
                                    <p className="font-medium">VATSIM Europe</p>
                                </div>
                            </div>

                            <Separator className="my-2" />

                            <div className="w-full space-y-2">
                                <Button
                                    asChild
                                    variant="outline"
                                    className="w-full"
                                >
                                    <Link href="/settings/account">
                                        <UserRound className="mr-2 h-4 w-4" />
                                        Edit profile
                                    </Link>
                                </Button>

                                <Button
                                    asChild
                                    variant="outline"
                                    className="w-full"
                                >
                                    <Link href="/settings/account">
                                        <BellDot className="mr-2 h-4 w-4" />
                                        Notification settings
                                    </Link>
                                </Button>
                            </div>
                        </CardHeader>
                    </Card>
                </div>

                <div className="col-span-12 lg:col-span-10">
                    <Tabs defaultValue="personal" className="mb-5">
                        <TabsList className="w-full justify-start">
                            <TabsTrigger value="personal">Personal</TabsTrigger>
                            <TabsTrigger value="account">Account</TabsTrigger>
                            <TabsTrigger value="security">Security</TabsTrigger>
                            <TabsTrigger value="password">Password</TabsTrigger>
                        </TabsList>

                        <TabsContent
                            value="personal"
                            className="mt-5 space-y-4"
                        >
                            <Card>
                                <CardHeader>
                                    <CardTitle>Personal information</CardTitle>
                                    <CardDescription>
                                        Update your public profile details and
                                        controller information.
                                    </CardDescription>
                                </CardHeader>

                                <CardContent className="space-y-6">
                                    <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                                        <div className="space-y-2">
                                            <Label htmlFor="name">
                                                Display name
                                            </Label>
                                            <Input
                                                id="name"
                                                defaultValue={user.name}
                                            />
                                        </div>

                                        <div className="space-y-2">
                                            <Label htmlFor="email">Email</Label>
                                            <div className="relative">
                                                <Mail className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                                <Input
                                                    id="email"
                                                    className="pl-9"
                                                    defaultValue={user.email}
                                                />
                                            </div>
                                        </div>

                                        <div className="space-y-2">
                                            <Label htmlFor="location">
                                                Location
                                            </Label>
                                            <div className="relative">
                                                <MapPin className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                                <Input
                                                    id="location"
                                                    className="pl-9"
                                                    defaultValue={user.location}
                                                />
                                            </div>
                                        </div>

                                        <div className="space-y-2">
                                            <Label htmlFor="fir">FIR</Label>
                                            <Input
                                                id="fir"
                                                defaultValue={user.fir}
                                            />
                                        </div>
                                    </div>

                                    <div className="space-y-2">
                                        <Label htmlFor="bio">Bio</Label>
                                        <Textarea
                                            id="bio"
                                            defaultValue={user.bio}
                                            className="min-h-[110px]"
                                        />
                                    </div>

                                    <div className="flex items-center justify-end gap-2">
                                        <Button variant="ghost">Cancel</Button>
                                        <Button>Save changes</Button>
                                    </div>
                                </CardContent>
                            </Card>

                            <Card>
                                <CardHeader>
                                    <CardTitle>Public profile</CardTitle>
                                    <CardDescription>
                                        Control what other members can see on
                                        your profile page.
                                    </CardDescription>
                                </CardHeader>
                                <CardContent className="space-y-4">
                                    <div className="flex items-center justify-between gap-4">
                                        <div className="space-y-1">
                                            <p className="text-sm font-medium">
                                                Show email
                                            </p>
                                            <p className="text-sm text-muted-foreground">
                                                Display your email on internal
                                                staff pages.
                                            </p>
                                        </div>
                                        <Switch defaultChecked />
                                    </div>

                                    <Separator />

                                    <div className="flex items-center justify-between gap-4">
                                        <div className="space-y-1">
                                            <p className="text-sm font-medium">
                                                Show FIR and rating
                                            </p>
                                            <p className="text-sm text-muted-foreground">
                                                Display your FIR and ATC rating
                                                on your profile.
                                            </p>
                                        </div>
                                        <Switch defaultChecked />
                                    </div>
                                </CardContent>
                            </Card>

                            <Card className="border-destructive">
                                <CardHeader>
                                    <CardTitle className="text-destructive">
                                        Danger Zone
                                    </CardTitle>
                                    <CardDescription>
                                        Irreversible and destructive actions
                                    </CardDescription>
                                </CardHeader>
                                <CardContent className="space-y-4">
                                    <div className="flex items-center justify-between gap-4">
                                        <div className="space-y-1">
                                            <p className="text-sm font-medium">
                                                Delete Account
                                            </p>
                                            <p className="text-sm text-muted-foreground">
                                                Permanently erase your account
                                                and all associated data in
                                                compliance with Article 17 GDPR.
                                            </p>
                                        </div>
                                        <Dialog>
                                            <DialogTrigger asChild>
                                                <Button variant={'destructive'}>
                                                    <Trash2 />
                                                    Delete Account
                                                </Button>
                                            </DialogTrigger>
                                            <DialogContent>
                                                <DialogHeader>
                                                    <DialogTitle>
                                                        Are you sure?
                                                    </DialogTitle>

                                                    <DialogDescription>
                                                        This action is
                                                        irreversible and will
                                                        remove all your data
                                                        associated with vatger.
                                                    </DialogDescription>

                                                    <Alert
                                                        variant="destructive"
                                                        className="my-3"
                                                    >
                                                        <InfoIcon />
                                                        <AlertDescription>
                                                            <p>
                                                                Your data will
                                                                be removed from
                                                                all vatger
                                                                services
                                                                including, but
                                                                not limited to:
                                                            </p>
                                                            <ul className="list-inside list-disc text-sm">
                                                                <li>Forum</li>
                                                                <li>Moodle</li>
                                                            </ul>
                                                        </AlertDescription>
                                                    </Alert>

                                                    <DialogFooter>
                                                        <Button
                                                            variant="destructive"
                                                            disabled
                                                        >
                                                            <Spinner />
                                                            Confirm Deletion
                                                        </Button>
                                                    </DialogFooter>
                                                </DialogHeader>
                                            </DialogContent>
                                        </Dialog>
                                    </div>
                                </CardContent>
                            </Card>
                        </TabsContent>

                        <TabsContent value="account" className="mt-5 space-y-4">
                            <Card>
                                <CardHeader>
                                    <CardTitle>Account preferences</CardTitle>
                                    <CardDescription>
                                        Configure your dashboard and
                                        communication preferences.
                                    </CardDescription>
                                </CardHeader>

                                <CardContent className="space-y-4">
                                    <div className="flex items-center justify-between gap-4">
                                        <div className="space-y-1">
                                            <p className="text-sm font-medium">
                                                Weekly digest
                                            </p>
                                            <p className="text-sm text-muted-foreground">
                                                Receive a weekly summary of
                                                events and training updates.
                                            </p>
                                        </div>
                                        <Switch defaultChecked />
                                    </div>

                                    <Separator />

                                    <div className="flex items-center justify-between gap-4">
                                        <div className="space-y-1">
                                            <p className="text-sm font-medium">
                                                Event notifications
                                            </p>
                                            <p className="text-sm text-muted-foreground">
                                                Notify me about upcoming events
                                                in my FIR.
                                            </p>
                                        </div>
                                        <Switch defaultChecked />
                                    </div>

                                    <Separator />

                                    <div className="flex items-center justify-between gap-4">
                                        <div className="space-y-1">
                                            <p className="text-sm font-medium">
                                                Training reminders
                                            </p>
                                            <p className="text-sm text-muted-foreground">
                                                Remind me about pending theory
                                                or practical sessions.
                                            </p>
                                        </div>
                                        <Switch />
                                    </div>

                                    <div className="flex items-center justify-end gap-2 pt-2">
                                        <Button variant="ghost">Reset</Button>
                                        <Button>Save preferences</Button>
                                    </div>
                                </CardContent>
                            </Card>
                        </TabsContent>

                        <TabsContent
                            value="security"
                            className="mt-5 space-y-4"
                        >
                            <Card>
                                <CardHeader>
                                    <CardTitle>Security</CardTitle>
                                    <CardDescription>
                                        Manage sign-in security and verification
                                        settings.
                                    </CardDescription>
                                </CardHeader>

                                <CardContent className="space-y-6">
                                    <div className="rounded-lg border p-4">
                                        <div className="flex items-start justify-between gap-4">
                                            <div className="flex items-start gap-3">
                                                <ShieldCheck className="mt-0.5 h-5 w-5 text-muted-foreground" />
                                                <div className="space-y-1">
                                                    <p className="text-sm font-medium">
                                                        Two-factor
                                                        authentication
                                                    </p>
                                                    <p className="text-sm text-muted-foreground">
                                                        Add an extra layer of
                                                        security to your
                                                        account.
                                                    </p>
                                                </div>
                                            </div>
                                            <Switch />
                                        </div>
                                    </div>

                                    <div className="rounded-lg border p-4">
                                        <div className="flex items-start justify-between gap-4">
                                            <div className="flex items-start gap-3">
                                                <KeyRound className="mt-0.5 h-5 w-5 text-muted-foreground" />
                                                <div className="space-y-1">
                                                    <p className="text-sm font-medium">
                                                        Active sessions
                                                    </p>
                                                    <p className="text-sm text-muted-foreground">
                                                        You are signed in on 2
                                                        devices.
                                                    </p>
                                                </div>
                                            </div>
                                            <Button variant="outline">
                                                Manage
                                            </Button>
                                        </div>
                                    </div>

                                    <div className="flex items-center justify-end gap-2">
                                        <Button variant="outline">
                                            Download recovery codes
                                        </Button>
                                        <Button>Save security settings</Button>
                                    </div>
                                </CardContent>
                            </Card>
                        </TabsContent>

                        <TabsContent
                            value="password"
                            className="mt-5 space-y-4"
                        >
                            <Card>
                                <CardHeader>
                                    <CardTitle>Change password</CardTitle>
                                    <CardDescription>
                                        Choose a strong password you don’t use
                                        elsewhere.
                                    </CardDescription>
                                </CardHeader>

                                <CardContent className="space-y-5"></CardContent>
                            </Card>
                        </TabsContent>
                    </Tabs>
                </div>
            </div>
        </>
    );
}
