'use client';

import {
    Field,
    FieldDescription,
    FieldError,
    FieldGroup,
    FieldLabel,
    FieldLegend,
    FieldSeparator,
    FieldSet,
} from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Controller, useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import z from 'zod';
import { Button } from '@/components/ui/button';
import React from 'react';
import { Spinner } from '@/components/ui/spinner';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { Separator } from '@/components/ui/separator';
import { useSearchParams } from 'next/navigation';
import { Card, CardContent } from '@/components/ui/card';
import PageHeader from '../_components/page-header';

const personalDetailsSchema = z.object({
    name: z.string().min(10, 'Minimum length of 10 characters'),
    email: z.string().email('Please enter a valid email address'),
    cid: z.number().nullable(),
    fruit: z.enum(['apple', 'banana', 'blueberry', 'grapes', '']),
    bio: z.string().min(25),
});

type FormValues = z.infer<typeof personalDetailsSchema>;

export default function Page() {
    const params = useSearchParams();

    console.log(params.get('name'));

    const [submitting, setSubmitting] = React.useState<boolean>(false);

    const form = useForm<FormValues>({
        resolver: zodResolver(personalDetailsSchema),
        defaultValues: {
            name: params.get('name') ?? '',
            cid: null,
            email: '',
            fruit: '',
        },
    });

    return (
        <>
            <PageHeader imageSrc={'/hero/hero_4.png'} title="Support" />

            <Card>
                <CardContent>
                    <form
                        onSubmit={form.handleSubmit((data) => {
                            console.log(data);
                            setSubmitting(true);
                        })}
                    >
                        <FieldSet>
                            <FieldGroup className="grid grid-cols-3 gap-4">
                                <Field>
                                    <FieldLabel htmlFor="name">Name</FieldLabel>
                                    <Input
                                        id="name"
                                        {...form.register('name')}
                                        aria-invalid={
                                            form.formState.errors.name !==
                                            undefined
                                        }
                                    />

                                    <FieldError
                                        errors={[form.formState.errors.name]}
                                    />
                                </Field>
                                <Field>
                                    <FieldLabel htmlFor="cid">
                                        VATSIM ID
                                    </FieldLabel>
                                    <Input
                                        id="cid"
                                        {...form.register('cid')}
                                        aria-invalid={
                                            form.formState.errors.cid !==
                                            undefined
                                        }
                                    />

                                    <FieldError
                                        errors={[form.formState.errors.name]}
                                    />
                                </Field>

                                <Field orientation="vertical">
                                    <FieldLabel htmlFor="email">
                                        E-Mail
                                    </FieldLabel>
                                    <Input
                                        id="email"
                                        {...form.register('email')}
                                        aria-invalid={
                                            form.formState.errors.email !==
                                            undefined
                                        }
                                    />
                                    <FieldError
                                        errors={[form.formState.errors.email]}
                                    />
                                </Field>
                            </FieldGroup>
                            <Field orientation="vertical">
                                <FieldLabel htmlFor="fruit">Subject</FieldLabel>
                                <Controller
                                    name={'fruit'}
                                    control={form.control}
                                    render={({ field }) => (
                                        <Select
                                            value={field.value}
                                            onValueChange={field.onChange}
                                        >
                                            <SelectTrigger
                                                aria-invalid={
                                                    form.formState.errors
                                                        .fruit !== undefined
                                                }
                                            >
                                                <SelectValue
                                                    placeholder={
                                                        'Choose your subject'
                                                    }
                                                />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectGroup>
                                                    <SelectItem value="apple">
                                                        Membership
                                                    </SelectItem>
                                                    <SelectItem value="banana">
                                                        ATC training
                                                    </SelectItem>
                                                    <SelectItem value="blueberry">
                                                        Pilot training
                                                    </SelectItem>
                                                    <SelectItem value="other">
                                                        Other
                                                    </SelectItem>
                                                </SelectGroup>
                                            </SelectContent>
                                        </Select>
                                    )}
                                />
                                <FieldError
                                    errors={[form.formState.errors.fruit]}
                                />
                            </Field>
                        </FieldSet>

                        <Separator className={'my-5'} />

                        <FieldSet>
                            <FieldLegend className={'font-bold'}>
                                Explain your issue here:
                            </FieldLegend>
                            <Field orientation="vertical">
                                <Textarea
                                    id="bio"
                                    {...form.register('bio')}
                                    aria-invalid={
                                        form.formState.errors.bio !== undefined
                                    }
                                />
                                <FieldError
                                    errors={[form.formState.errors.bio]}
                                />
                            </Field>
                        </FieldSet>

                        <FieldSeparator className={'my-10'} />

                        <Button
                            type={'submit'}
                            variant={'default'}
                            disabled={submitting}
                        >
                            {submitting && <Spinner />}
                            Submit
                        </Button>
                    </form>
                </CardContent>
            </Card>
        </>
    );
}
