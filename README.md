Symfony Acl Bundle
==================

This bundle is intended to provide a replacement to the [Symfony2 ACL
Component](http://symfony.com/doc/current/cookbook/security/acl.html).

Symfony2's ACL Component is currently weakly documented, hard to extend,  and
present a leaky abstraction.

All of these issues makes it hard to maintain. In this context, we offer a new
alternative, whose abstraction layer has been redesigned from scratch and
offering easy extension points.

This component can be used either directly, requiring only the Symfony Core
to be used. Or with the full-stack Symfony2 framework.

This component is relying on 5 concepts:

 - ACL and ACEs, Grantees and Targets
 - Permissions, Permission Map and Attributes
 - ACL Provider
 - Voter and Access Granting Strategies

ACL and ACEs, Grantees and Targets
----------------------------------

An ACL (Access Control List), is a list of ACEs (Access Control Entry).

An ACE is a triplet [Grantee, Target, Permission]. The Grantee is whom the
permission is granted to. The Target is what the permission is granted on. The
permission is obviously, what is granted.

For example, [UserA, Post1, VIEW] would mean that UserA is allowed to VIEW
Post1.

Of course, the ACL system is internally using an abstraction for Grantees and
Targets. But you don't need to worry about creating these yourself, the ACL
Provider will take care of everything for you. You only need to know what is
accepted as a Grantee or as a Target.

### Grantees ###

Grantees are abstracted away as SecurityIdentities. There are three kinds of
security identity:

 - Anonymous, which will be used for anonymous users.
 - [Role](http://symfony.com/doc/current/book/security.html#roles).
 - [User](http://symfony.com/doc/current/book/security.html#users).

Thus, accepted grantees are:

 - `null`, denoting an _Anonymous_ identity.
 - A string, denoting the name of a _Role_.
 - A Role instance, denoting the _Role_.
 - A User instance, denoting the _User_.
 - A Token instance, denoting the _User_ associated to the Token, or an
   _Anonymous_ identity if there is no such User.

Any implementation of
[UserInterface](http://api.symfony.com/2.4/Symfony/Component/Security/Core/User/UserInterface.html)
will be seamlessly accepted by the ACL system. The User is identified using its
class and ID (or Username if there is no `getId()` method). If you prefer to
identify your User using something else, you should implement
[AclSecurityObjectInterface](src/Model/AclSecurityObjectInterface.php).

Any implementation of
[RoleInterface](http://api.symfony.com/2.4/Symfony/Component/Security/Core/Role/RoleInterface.html)
will be seamlessly accepted by the ACL system, as long as `getRole()` returns a
string. If you prefer to identify your Role using something else, you should
implement
[AclSecurityObjectInterface](src/Model/AclSecurityObjectInterface.php).

### Target ###

Targets are abstracted away as TargetIdentities. ACLs can be granted on 4 kinds
of targets:

 - A Domain Object (i.e. an object you usually manipulate in your application).
 - A Domain Class (i.e. the class of an usual object).
 - A Domain Object's field (i.e. a given field on an usual object).
 - A Domain Class' field. (i.e. a given field on an usual class).

Thus, accepted target are:

 - A string, denoting the name of a _Class_
 - An object, denoting the _Object_.
 - An array of two elements: an object and a string, denoting the name of the
   _Object's field_.
 - An array of two strings: denoting the names of, respectively, _a Class and
   its Field_.

For objects to be accepted, they need to either implements
[AclTargetObjectInterface](src/Model/AclTargetObjectInterface.php), have a
`getId()` or a `__toString()` method.

N.B.: A field doesn't need to be actually a property within the class, but it
could be any string. This could be useful to specify permissions on parts of an
Object.

Permissions, Permission Map and Attributes
------------------------------------------

Symfony's Security system is based on "attributes", this is the first parameter
given to
[isGranted](http://symfony.com/doc/current/book/security.html#access-control).

However the mapping between granted permissions and attributes is not 1:1. For
example, following the [Symfony2's ACL Built-in Permission
Map](http://symfony.com/doc/current/cookbook/security/acl_advanced.html#built-in-permission-map),
if you grant someone the `OPERATOR` permission, your intent is to grant `VIEW`,
`EDIT`, `CREATE`, `DELETE`, `UNDELETE` and `OPERATOR` attributes.

Thus, one permissions actually denotes several attributes. This correspondance
is taken care of through the Permission Map. To this end, we provide a
re-implementation of the Symfony2's ACL Built-in Permission Map.

In the full stack Symfony2 framework, the permission map is available through 

ACL Provider
------------

The ACL Provider is the most central item of the ACL system. The ACL Provider is
the object responsible for fetching the ACEs from a backend storage (usually a
database) and providing easy access to them. A basic ACL Provider is considered
Read-Only, but the MutableAclProvider allows easy modification of the ACL
(i.e. creation and deletion of ACEs).

Voter and Access Granting Strategies
------------------------------------

Symfony's Security system is based on a set of
[Voters](http://symfony.com/doc/current/cookbook/security/voters.html): each
time `isGranted` is called, a set of voters are called until one decides on
granting or denying access.

A Voter is provided with a Token (denoting a User and its Role), a Target and a
set of Attributes.

The ACL default voter check if the current User or any of its Roles is granted a
permission providing any of the requested Attributes.

However, granting decisions for ACL could be quite complex. To make this easier,
the Voter is not implementing the whole access checking process, but delay the
final decision to an Access Granting Strategy.

The strategy is responsible for deciding if any of the User or Roles is actually
granted any of the attributes on the target.

We provide 5 built-in strategies:

### Plain

We check only the target ACEs themselves. So, given a class `C1` and one of its
instances `O1`, if a User is granted access on `C1`, but the permission is not
explicitly given on `O1`, he will not be granted the permission.

### Meta

We check for permissions on objects and their classes. If a User does not have
any permission granted on an Object (or an Object's Field), we will try to check
if they have any for the Class (or the Class' Field).

This is the default strategy in the full stack framework.

### Field erasure

We check for permissions on Fields and Object or Class. If a User does not have
any permission granted on an Object's (or Class)' Field, we will try to check if
they have any for the Object (or Class) itself.

### Inheritance

We check for permission on Parent classes. If a User does not have any
permission granted on a Class (or its Field), we will try to check on its Parent
Class (or its Field).

### Complex

This strategy is a mix the last three. It is mostly intended to be used as a
code example for your own Strategies. Please read the [source
code](src/Voter/Strategy/AclComplexAccessGrantingStrategy) for more details.
